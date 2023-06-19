<?php

namespace App\Services;

use App\Models\RegisterTamu\MRegisterTamu;

class ConfirmationAttendaceService_V1
{
    public function __construct() {

    }

    public function save() {
        $datetime = date('Y-m-d H:i:s');

        try {
            foreach ($params as $data) {
                $confirm_attend = $data['confirm_attend'];

                if (!$confirm_attend) {
                    $name = strip_tags($data['name']);
                    $no_register = $data['no_register'];
                    $no_urut = $data['no_order'];
                    $attendance = $data['attendance'] == 'Hadir' ? 'Y' : 'N';
                    $consumtion = isset($data['is_vegetarian']) ? $data['is_vegetarian'] : null;

                    MRegisterTamu::update_register_tamu_by_id_and_name(
                        $no_register,
                        $name,
                        $no_urut,
                        $attendance,
                        $consumtion,
                        $datetime
                    );
                }
            }
        } catch(\Exception $e) {
            return response($e->getMessage(), 400);
        }

        return response('Success update data.', 200);
    }
}