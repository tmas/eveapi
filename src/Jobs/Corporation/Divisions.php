<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Eveapi\Jobs\Corporation;

use Seat\Eveapi\Jobs\EsiBase;
use Seat\Eveapi\Models\Corporation\CorporationDivision;

class Divisions extends EsiBase {

    protected $method = 'get';

    protected $endpoint = '/corporations/{corporation_id}/divisions/';

    protected $version = 'v1';

    public function handle() {

        $divisions = $this->retrieve([
            'corporation_id' => $this->getCorporationId(),
        ]);

        if (property_exists($divisions, 'hangar'))
            collect($divisions->hangar)->each(function($hangar){
                CorporationDivision::firstOrNew([
                    'corporation_id' => $this->getCorporationId(),
                    'type'           => 'hangar',
                    'division'       => $hangar->division,
                ])->fill([
                    'name'           => $hangar->name ?? null,
                ])->save();
            });

        if (property_exists($divisions, 'wallet'))
            collect($divisions->wallet)->each(function($wallet){
                CorporationDivision::firstOrNew([
                    'corporation_id' => $this->getCorporationId(),
                    'type'           => 'wallet',
                    'division'       => $wallet->division,
                ])->fill([
                    'name'           => $wallet->name ?? null,
                ])->save();
            });

    }

}