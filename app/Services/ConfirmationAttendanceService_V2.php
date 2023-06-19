<?php

namespace App\Services;

use App\Models\RegisterTamu\MRegisterTamu;

class ConfirmationAttendanceService_V2
{
    public function save($data) {
        $datetime = date('Y-m-d H:i:s');

        try {
            $confirm_attend = $data['confirm_attend'];

            if ($confirm_attend == 'Y') {
                return response('You already submitted confirmation.', 200);
            }

            $name = strip_tags($data['name']);
            $no_register = $data['no_register'];
            $no_urut = $data['no_order'];
            $seat = $data['seat'];

            if ($seat >= 2) {
                $total_attendance = $data['total_attendance'];
                $total_vegetarian = $data['total_vegetarian'];
                $attendance = $total_attendance ? 'Y' : 'N';
                $consumtion = $total_vegetarian ? 'Y' : 'N';
            } else {
                $attendance = $data['attendance'] == 'Hadir' ? 'Y' : 'N';
                $consumtion = isset($data['is_vegetarian']) ? $data['is_vegetarian'] : null;
                $total_attendance = $attendance == 'Y' ? 1 : 0;
                $total_vegetarian = $consumtion == 'Y' ? 1 : 0;
            }

            MRegisterTamu::update_register_tamu_by_id_and_name(
                $no_register,
                $name,
                $no_urut,
                $attendance,
                $consumtion,
                $datetime,
                [
                    "JML_KONFIRMASI_HADIR" => $total_attendance,
                    "JML_VEGETARIAN" => $total_vegetarian
                ]
            );
        } catch(\Exception $e) {
            return response($e->getMessage(), 400);
        }

        return response('Thank you for submit confirmation.', 200);
    }
}