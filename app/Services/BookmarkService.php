<?php

namespace App\Services;

use App\Models\Sys\SysModel;

class BookmarkService
{
    public static function get_bookmarks_menu($user_id)
    {
		return SysModel::get_bookmarks_menu_by_user_id($user_id);
    }

    public static function print_bookmarks($user_id) 
	{
		$bookmarks = self::get_bookmarks_menu($user_id);

		if (!$bookmarks->isEmpty()) {
			$bookmark_element = '';
            session(['bookmarks_menu' => $bookmarks->toArray()]);

			echo '
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link bg-warning">
						<i class="nav-icon fas fa-bookmark"></i>
						<p>
							Bookmarks
							<i class="right fas fa-angle-left"></i>
						</p>
					</a>
					<ul class="nav-treeview">
			';

			foreach ($bookmarks as $item) {
				echo '
					<li class="nav-item">
						<a href="/' . $item->controller . '" class="nav-link">
							<i class="far fa-bookmark nav-icon text-warning"></i>
							<p>' . $item->nama_modul . '</p>
						</a>
					</li>
				';
			}

			echo '</ul></li>';
		}
	}

    public static function check_bookmarks($controller) 
	{
		$bookmarks_key = [];
        $user_id = session('user_id');
		$bookmarks = session('bookmarks_menu', self::get_bookmarks_menu($user_id)->toArray());

		if ($bookmarks) {
			$bookmarks = collect($bookmarks);
			$bookmarks_key = $bookmarks->map(function($data) {
				return $data->controller;
			})->toArray();
		}

		return in_array($controller, $bookmarks_key);
	}

    public static function toggle_bookmarks($user_id, $controller, $module_name) 
    {
        if (!self::check_bookmarks($controller)) {
            SysModel::add_bookmarks_menu($user_id, $controller, $module_name);
        } else {
            SysModel::delete_bookmarks_menu($user_id, $controller, $module_name);
        }
        
        session()->forget('bookmarks_menu');
    }
}