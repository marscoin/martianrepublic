<?php
namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use App\Models\CivicWallet;
use App\Includes\AppHelper;

class InventoryController extends Controller
{

	public function __construct()
	{
	}

	public function showAll()
	{
		$view = View::make('inventory.dashboard');
		$view->allItems = InventoryItem::orderBy('created_at', 'desc')->get();
		$view->categories = InventoryItem::CATEGORIES;
		$view->conditions = InventoryItem::CONDITIONS;

		if (Auth::check()) {
			$uid = Auth::user()->id;
			$profile = Profile::where('userid', '=', $uid)->first();
			$wallet = CivicWallet::where('user_id', '=', $uid)->first();

			if (!$profile) {
				return redirect('/twofa');
			}
			if ($profile->openchallenge == 1 || is_null($profile->openchallenge)) {
				return redirect('/twofachallenge');
			}

			$view->wallet_open = $profile->civic_wallet_open;
			$view->isCitizen = $profile->citizen;
			$view->isGP = $profile->general_public;
			$view->balance = 0;
			$view->public_address = "";

			if ($wallet) {
				$view->balance = AppHelper::getMarscoinBalance($wallet['public_addr']);
				$view->public_address = $wallet['public_addr'];
			}

			$view->myItems = InventoryItem::where('userid', '=', $uid)->orderBy('created_at', 'desc')->get();
		} else {
			$view->wallet_open = false;
			$view->isCitizen = false;
			$view->isGP = false;
			$view->balance = 0;
			$view->public_address = "";
			$view->myItems = collect();
		}

		return $view;
	}

	public function store(Request $request)
	{
		if (!Auth::check()) {
			return redirect('/login');
		}

		$uid = Auth::user()->id;
		$profile = Profile::where('userid', '=', $uid)->first();

		if (!$profile || !$profile->civic_wallet_open) {
			return redirect('/inventory/all')->with('error', 'Wallet must be unlocked to add items.');
		}

		$request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string|max:2000',
			'category' => 'required|string|in:' . implode(',', array_keys(InventoryItem::CATEGORIES)),
			'quantity' => 'required|integer|min:1|max:999999',
			'unit' => 'nullable|string|max:50',
			'location' => 'nullable|string|max:255',
			'condition' => 'required|string|in:' . implode(',', array_keys(InventoryItem::CONDITIONS)),
		]);

		InventoryItem::create([
			'userid' => $uid,
			'name' => $request->input('name'),
			'description' => $request->input('description'),
			'category' => $request->input('category'),
			'quantity' => $request->input('quantity'),
			'unit' => $request->input('unit'),
			'location' => $request->input('location'),
			'condition' => $request->input('condition'),
		]);

		return redirect('/inventory/all')->with('success', 'Item added to inventory.');
	}

	public function update(Request $request, $id)
	{
		if (!Auth::check()) {
			return redirect('/login');
		}

		$uid = Auth::user()->id;
		$item = InventoryItem::where('id', $id)->where('userid', $uid)->first();

		if (!$item) {
			return redirect('/inventory/all')->with('error', 'Item not found.');
		}

		$request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string|max:2000',
			'category' => 'required|string|in:' . implode(',', array_keys(InventoryItem::CATEGORIES)),
			'quantity' => 'required|integer|min:1|max:999999',
			'unit' => 'nullable|string|max:50',
			'location' => 'nullable|string|max:255',
			'condition' => 'required|string|in:' . implode(',', array_keys(InventoryItem::CONDITIONS)),
		]);

		$item->update($request->only(['name', 'description', 'category', 'quantity', 'unit', 'location', 'condition']));

		return redirect('/inventory/all')->with('success', 'Item updated.');
	}

	public function destroy($id)
	{
		if (!Auth::check()) {
			return redirect('/login');
		}

		$uid = Auth::user()->id;
		$item = InventoryItem::where('id', $id)->where('userid', $uid)->first();

		if (!$item) {
			return redirect('/inventory/all')->with('error', 'Item not found.');
		}

		$item->delete();

		return redirect('/inventory/all')->with('success', 'Item removed from inventory.');
	}
}
