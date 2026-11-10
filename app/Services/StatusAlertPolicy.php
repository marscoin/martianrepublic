<?php

namespace App\Services;

/**
 * Decides when a failing subsystem is worth an email.
 *
 * The status page shows what is down to whoever looks. This turns it into a
 * message: once when something has stayed down long enough not to be a blip,
 * again every few hours while it remains down, and once when it recovers.
 *
 * Pure: state goes in and comes out, so the rules can be tested without mail
 * or a clock.
 */
class StatusAlertPolicy
{
    public const DOWN_FOR_SECONDS = 600;        // ignore anything shorter

    public const REMIND_EVERY_SECONDS = 21600;  // six hours

    /**
     * @param  array<string,bool>  $up  component name => healthy?
     * @param  array{since?:array<string,int>,alerted?:array<string,int>}  $state
     * @return array{state:array,down:string[],recovered:string[]}
     *                                                             `down` lists components to report now; `recovered` those back up
     *                                                             after having been reported.
     */
    public static function evaluate(array $up, array $state, int $now): array
    {
        $since = $state['since'] ?? [];
        $alerted = $state['alerted'] ?? [];
        $down = [];
        $recovered = [];

        foreach ($up as $name => $healthy) {
            if ($healthy) {
                if (isset($alerted[$name])) {
                    $recovered[] = $name;
                }
                unset($since[$name], $alerted[$name]);

                continue;
            }
            $since[$name] ??= $now;
            if ($now - $since[$name] < self::DOWN_FOR_SECONDS) {
                continue;
            }
            if (! isset($alerted[$name]) || $now - $alerted[$name] >= self::REMIND_EVERY_SECONDS) {
                $down[] = $name;
                $alerted[$name] = $now;
            }
        }

        // Forget components that are no longer checked.
        $since = array_intersect_key($since, $up);
        $alerted = array_intersect_key($alerted, $up);

        return ['state' => ['since' => $since, 'alerted' => $alerted], 'down' => $down, 'recovered' => $recovered];
    }
}
