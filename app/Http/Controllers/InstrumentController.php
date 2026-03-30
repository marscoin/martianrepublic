<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
use App\Models\CivicWallet;
use App\Models\Bads\OversightCommittee;
use App\Models\Bads\Deputy;
use App\Models\Bads\Instrument;
use App\Models\Bads\Attestation;
use App\Models\Bads\Anomaly;
use App\Models\Bads\CalibrationRecord;
use App\Includes\AppHelper;

class InstrumentController extends Controller
{
    /**
     * Device category mapping from hex prefix to human-readable category.
     */
    public const DEVICE_CATEGORIES = [
        'atmospheric'  => ['prefix' => 0x01, 'label' => 'Atmospheric',  'icon' => 'fa-wind',       'color' => '#00e4ff'],
        'power'        => ['prefix' => 0x02, 'label' => 'Power',        'icon' => 'fa-bolt',       'color' => '#f59e0b'],
        'agricultural' => ['prefix' => 0x03, 'label' => 'Agricultural', 'icon' => 'fa-seedling',   'color' => '#34d399'],
        'structural'   => ['prefix' => 0x04, 'label' => 'Structural',   'icon' => 'fa-building',   'color' => '#8b5cf6'],
        'water'        => ['prefix' => 0x05, 'label' => 'Water',        'icon' => 'fa-droplet',    'color' => '#3b82f6'],
        'medical'      => ['prefix' => 0x06, 'label' => 'Medical',      'icon' => 'fa-heart-pulse','color' => '#ef4444'],
        'environmental'=> ['prefix' => 0x08, 'label' => 'Environmental','icon' => 'fa-globe',      'color' => '#f97316'],
    ];

    /**
     * Common auth/profile data loader (mirrors CongressController pattern).
     */
    private function loadUserContext()
    {
        $ctx = new \stdClass();
        $ctx->wallet_open = false;
        $ctx->isCitizen = false;
        $ctx->isDeputy = false;
        $ctx->deputyRecord = null;
        $ctx->public_address = '';

        if (Auth::check()) {
            $uid = Auth::user()->id;
            $profile = Profile::where('userid', $uid)->first();

            if (!$profile) {
                $ctx->redirect = redirect('/twofa');
                return $ctx;
            }
            if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
                $ctx->redirect = redirect('/twofachallenge');
                return $ctx;
            }

            $ctx->wallet_open = $profile->civic_wallet_open;
            $ctx->isCitizen = $profile->citizen;

            $wallet = CivicWallet::where('user_id', $uid)->first();
            if ($wallet) {
                $ctx->public_address = $wallet->public_addr;
            }

            $ctx->deputyRecord = Deputy::where('user_id', $uid)->where('status', 'active')->first();
            $ctx->isDeputy = $ctx->deputyRecord !== null;
        }

        return $ctx;
    }

    /**
     * List all instruments with filtering, grouping, and chain-of-trust stats.
     */
    public function index()
    {
        $ctx = $this->loadUserContext();
        if (isset($ctx->redirect)) return $ctx->redirect;

        // Load instruments with relationships
        $instruments = Instrument::with(['certifiedBy.user', 'certifiedBy.committee'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Category counts
        $categoryCounts = Instrument::select('device_category', DB::raw('COUNT(*) as cnt'))
            ->groupBy('device_category')
            ->pluck('cnt', 'device_category')
            ->toArray();

        // Chain of trust stats
        $stats = [
            'committees'   => OversightCommittee::where('status', 'active')->count(),
            'deputies'     => Deputy::where('status', 'active')->count(),
            'instruments'  => Instrument::count(),
            'attestations' => Attestation::count(),
        ];

        // Committees for sidebar
        $committees = OversightCommittee::withCount(['deputies', 'instruments'])
            ->where('status', 'active')
            ->get();

        $view = View::make('inventory.instruments');
        $view->instruments = $instruments;
        $view->categoryCounts = $categoryCounts;
        $view->stats = $stats;
        $view->committees = $committees;
        $view->categories = self::DEVICE_CATEGORIES;
        $view->wallet_open = $ctx->wallet_open;
        $view->isCitizen = $ctx->isCitizen;
        $view->isDeputy = $ctx->isDeputy;

        return $view;
    }

    /**
     * Show single instrument detail with full chain of trust.
     */
    public function show($id)
    {
        $ctx = $this->loadUserContext();
        if (isset($ctx->redirect)) return $ctx->redirect;

        $instrument = Instrument::with([
            'certifiedBy.user',
            'certifiedBy.committee.proposal',
        ])->findOrFail($id);

        $attestations = Attestation::where('instrument_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $anomalies = Anomaly::where('instrument_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $calibrations = CalibrationRecord::where('instrument_id', $id)
            ->with('calibrator.user')
            ->orderBy('calibrated_at', 'desc')
            ->get();

        // Build chain of trust path
        $chain = [];
        if ($instrument->certifiedBy && $instrument->certifiedBy->committee) {
            $committee = $instrument->certifiedBy->committee;
            $chain['proposal'] = $committee->proposal;
            $chain['committee'] = $committee;
            $chain['deputy'] = $instrument->certifiedBy;
        }
        $chain['instrument'] = $instrument;

        $view = View::make('inventory.instrument-detail');
        $view->instrument = $instrument;
        $view->attestations = $attestations;
        $view->anomalies = $anomalies;
        $view->calibrations = $calibrations;
        $view->chain = $chain;
        $view->categories = self::DEVICE_CATEGORIES;
        $view->wallet_open = $ctx->wallet_open;
        $view->isCitizen = $ctx->isCitizen;
        $view->isDeputy = $ctx->isDeputy;

        return $view;
    }

    /**
     * Show instrument registration form (deputy-only).
     */
    public function create()
    {
        $ctx = $this->loadUserContext();
        if (isset($ctx->redirect)) return $ctx->redirect;

        if (!$ctx->isDeputy) {
            return redirect()->route('instruments.index')
                ->with('error', 'Only active deputies may certify new instruments.');
        }

        // Committees the current user is deputy of
        $myCommittees = OversightCommittee::whereHas('deputies', function ($q) {
            $q->where('user_id', Auth::id())->where('status', 'active');
        })->get();

        $view = View::make('inventory.instrument-create');
        $view->deviceTypes = Instrument::DEVICE_TYPES;
        $view->categories = self::DEVICE_CATEGORIES;
        $view->myCommittees = $myCommittees;
        $view->wallet_open = $ctx->wallet_open;
        $view->isCitizen = $ctx->isCitizen;
        $view->isDeputy = $ctx->isDeputy;

        return $view;
    }

    /**
     * Store a new instrument registration.
     */
    public function store(Request $request)
    {
        $ctx = $this->loadUserContext();
        if (isset($ctx->redirect)) return $ctx->redirect;

        if (!$ctx->isDeputy) {
            return redirect()->route('instruments.index')
                ->with('error', 'Only active deputies may certify new instruments.');
        }

        $request->validate([
            'device_type'      => 'required|integer',
            'serial'           => 'required|string|max:255',
            'make'             => 'nullable|string|max:255',
            'model'            => 'nullable|string|max:255',
            'location'         => 'nullable|string|max:255',
            'firmware_version' => 'nullable|string|max:50',
            'mqtt_namespace'   => 'nullable|string|max:255',
        ]);

        $deviceType = (int) $request->input('device_type');
        $deviceTypeName = Instrument::DEVICE_TYPES[$deviceType] ?? 'Unknown';

        // Derive category from device type hex prefix
        $prefix = $deviceType >> 8;
        $deviceCategory = 'unknown';
        foreach (self::DEVICE_CATEGORIES as $key => $cat) {
            if ($cat['prefix'] === $prefix) {
                $deviceCategory = $key;
                break;
            }
        }

        // Generate a Marscoin address for the device
        $address = 'M' . substr(hash('sha256', uniqid('bads_', true) . $request->input('serial')), 0, 33);

        $instrument = Instrument::create([
            'address'               => $address,
            'device_type'           => $deviceType,
            'device_type_name'      => $deviceTypeName,
            'device_category'       => $deviceCategory,
            'make'                  => $request->input('make'),
            'model'                 => $request->input('model'),
            'serial'                => $request->input('serial'),
            'location'              => $request->input('location'),
            'firmware_version'      => $request->input('firmware_version'),
            'mqtt_namespace'        => $request->input('mqtt_namespace'),
            'certified_by_deputy_id'=> $ctx->deputyRecord->id,
            'status'                => 'active',
        ]);

        return redirect()->route('instruments.show', $instrument->id)
            ->with('success', 'Instrument certified and registered successfully.');
    }

    /**
     * List all oversight committees with their deputies and instrument counts.
     */
    public function committees()
    {
        $ctx = $this->loadUserContext();
        if (isset($ctx->redirect)) return $ctx->redirect;

        $committees = OversightCommittee::with(['deputies.user', 'proposal'])
            ->withCount(['deputies', 'instruments'])
            ->orderBy('name')
            ->get();

        $view = View::make('inventory.committees');
        $view->committees = $committees;
        $view->wallet_open = $ctx->wallet_open;
        $view->isCitizen = $ctx->isCitizen;
        $view->isDeputy = $ctx->isDeputy;

        return $view;
    }

    /**
     * JSON endpoint: full chain of trust for an instrument.
     */
    public function chainOfTrust($instrumentId)
    {
        $instrument = Instrument::with([
            'certifiedBy.user',
            'certifiedBy.committee.proposal',
            'attestations' => function ($q) { $q->orderBy('created_at', 'desc')->limit(10); },
        ])->findOrFail($instrumentId);

        $chain = [
            'instrument' => [
                'id'       => $instrument->id,
                'address'  => $instrument->address,
                'name'     => $instrument->device_type_name,
                'serial'   => $instrument->serial,
                'status'   => $instrument->status,
                'cert_txid'=> $instrument->cert_txid,
            ],
        ];

        if ($instrument->certifiedBy) {
            $deputy = $instrument->certifiedBy;
            $chain['deputy'] = [
                'id'            => $deputy->id,
                'civic_address' => $deputy->civic_address,
                'user_name'     => $deputy->user ? $deputy->user->fullname : 'Unknown',
                'role_tag'      => $deputy->role_tag,
                'appointment_txid' => $deputy->appointment_txid,
            ];

            if ($deputy->committee) {
                $committee = $deputy->committee;
                $chain['committee'] = [
                    'id'           => $committee->id,
                    'name'         => $committee->name,
                    'slug'         => $committee->slug,
                    'proposal_txid'=> $committee->proposal_txid,
                ];

                if ($committee->proposal) {
                    $chain['proposal'] = [
                        'id'    => $committee->proposal->id,
                        'title' => $committee->proposal->title,
                        'status'=> $committee->proposal->status,
                        'txid'  => $committee->proposal->ipfs_hash ?? null,
                    ];
                }
            }
        }

        $chain['attestations'] = $instrument->attestations->map(function ($a) {
            return [
                'id'          => $a->id,
                'txid'        => $a->txid,
                'merkle_root' => $a->merkle_root,
                'readings'    => $a->reading_count,
                'verified'    => $a->verified,
                'batch_start' => $a->batch_start ? $a->batch_start->toIso8601String() : null,
                'batch_end'   => $a->batch_end ? $a->batch_end->toIso8601String() : null,
            ];
        });

        return response()->json($chain);
    }
}
