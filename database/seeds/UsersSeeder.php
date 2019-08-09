<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = new Role();
		$adminRole->name = "admin";
		$adminRole->display_name = "admin";
		$adminRole->save();

        $karyawanRole = new Role();
		$karyawanRole->name = "karyawan";
		$karyawanRole->display_name = "karyawan";
		$karyawanRole->save();

        $admin = new User();
		$admin->name = 'Admin ARAN';
		$admin->email = 'admin_aran';
		$admin->password = bcrypt('ardi1995');
		$admin->status = '1';
        $admin->save();
        $admin->attachRole($adminRole);

        $karyawan = new User();
		$karyawan->name = 'Ardiansyah Pratama';
		$karyawan->email = 'ardi1995';
		$karyawan->password = bcrypt('ardi1995');
		$karyawan->status = '1';
        $karyawan->no_pegawai = 'AP0001';
        $karyawan->save();
        $karyawan->attachRole($karyawanRole);

        $karyawan2 = new User();
		$karyawan2->name = 'Ardiansyah Pratama 2';
		$karyawan2->email = 'ardi19952';
		$karyawan2->password = bcrypt('ardi1995');
		$karyawan2->status = '1';
        $karyawan2->save();
        $karyawan2->attachRole($karyawanRole);

        $karyawan3 = new User();
		$karyawan3->name = 'Ardiansyah Pratama 3';
		$karyawan3->email = 'ardi19953';
		$karyawan3->password = bcrypt('ardi1995');
		$karyawan3->status = '1';
        $karyawan3->save();
        $karyawan3->attachRole($karyawanRole);

        $karyawan4 = new User();
		$karyawan4->name = 'Ardiansyah Pratama 4';
		$karyawan4->email = 'ardi19954';
		$karyawan4->password = bcrypt('ardi1995');
		$karyawan4->status = '1';
        $karyawan4->save();
        $karyawan4->attachRole($karyawanRole);

        $karyawan5 = new User();
		$karyawan5->name = 'Ardiansyah Pratama 5';
		$karyawan5->email = 'ardi19955';
		$karyawan5->password = bcrypt('ardi1995');
		$karyawan5->status = '1';
        $karyawan5->save();
        $karyawan5->attachRole($karyawanRole);

        $karyawan6 = new User();
		$karyawan6->name = 'Ardiansyah Pratama 6';
		$karyawan6->email = 'ardi19956';
		$karyawan6->password = bcrypt('ardi1995');
		$karyawan6->status = '1';
        $karyawan6->save();
        $karyawan6->attachRole($karyawanRole);

        $karyawan7 = new User();
		$karyawan7->name = 'Ardiansyah Pratama 7';
		$karyawan7->email = 'ardi19957';
		$karyawan7->password = bcrypt('ardi1995');
		$karyawan7->status = '1';
        $karyawan7->save();
        $karyawan7->attachRole($karyawanRole);

        $karyawan8 = new User();
		$karyawan8->name = 'Ardiansyah Pratama 8';
		$karyawan8->email = 'ardi19958';
		$karyawan8->password = bcrypt('ardi1995');
		$karyawan8->status = '1';
        $karyawan8->save();
        $karyawan8->attachRole($karyawanRole);

        $karyawan9 = new User();
		$karyawan9->name = 'Ardiansyah Pratama 9';
		$karyawan9->email = 'ardi19959';
		$karyawan9->password = bcrypt('ardi1995');
		$karyawan9->status = '1';
        $karyawan9->save();
        $karyawan9->attachRole($karyawanRole);

        $karyawan10 = new User();
		$karyawan10->name = 'Ardiansyah Pratama 10';
		$karyawan10->email = 'ardi199510';
		$karyawan10->password = bcrypt('ardi1995');
		$karyawan10->status = '1';
        $karyawan10->save();
        $karyawan10->attachRole($karyawanRole);
    }
}
