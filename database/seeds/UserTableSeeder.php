<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->delete();
		
		// $user1 = App\User::create(['name' => 'Basi',  => 'Backend Developer' ,'dp_img' => ' ' ,  'email' => 'basi@bluroe.com', 'password' => Hash::make('nevergiveup')]);
		$user2 = App\User::create(['name' => 'Hanjaz' ,'dp_img' => ' ', 'email' => 'roshan@bluroe.com', 'password' => Hash::make('nevergiveup')]);
		$user3 = App\User::create(['name' => 'Anu',  'dp_img' => ' ', 'email' => 'anu@bluroe.com', 'password' => Hash::make('nevergiveup')]);
		$user3 = App\User::create(['name' => 'Waxx',  'dp_img' => ' ',  'email' => 'waxx@bluroe.com', 'password' => Hash::make('nevergiveup')]);
		$user3 = App\User::create(['name' => 'Shibi', 'dp_img' => ' ',  'email' => 'shibi@bluroe.com', 'password' => Hash::make('nevergiveup')]);
		$user3 = App\User::create(['name' => 'Rixx',  'dp_img' => ' ',  'email' => 'rinoy@bluroe.com', 'password' => Hash::make('nevergiveup')]);


		DB::table('courses')->delete();

		$user3 = App\Course::create( 
			['name' => 'Computer Science',  'description' => 'this is the description for computerscience',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Course::create( 
			['name' => 'Biology Science',  'description' => 'this is the description for biologyscience',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Course::create( 
			['name' => 'Commerce',  'description' => 'this is the description for commerce',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Course::create( 
			['name' => 'BCA',  'description' => 'this is the description for bca',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );

		DB::table('collages')->delete();

		$user3 = App\Collage::create( 
			['name' => 'silicon vally',  'description' => 'this is the description for siliconvally',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Collage::create( 
			['name' => 'Excell Collage',  'description' => 'this is the description for Excell',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Collage::create( 
			['name' => 'Crist Collage',  'description' => 'this is the description for Crist',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Collage::create( 
			['name' => 'Nalantha Collage',  'description' => 'this is the description for Nalantha',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		
		DB::table('categories')->delete();

		$user3 = App\Category::create( 
			['name' => 'Cat1',  'description' => 'this is the description for Cat1',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Category::create( 
			['name' => 'Cat2',  'description' => 'this is the description for Cat2',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Category::create( 
			['name' => 'Cat3',  'description' => 'this is the description for Cat3',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Category::create( 
			['name' => 'Cat4',  'description' => 'this is the description for Cat4',  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );

		DB::table('exams')->delete();

		$user3 = App\Exam::create( 
			['name' => 'ShajiExam',  'apply_date' => 111, 'exam_date' => 222,  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Exam::create( 
			['name' => 'SugunanExam',  'apply_date' => 333, 'exam_date' => 444,  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Exam::create( 
			['name' => 'RinasExam',  'apply_date' => 555, 'exam_date' => 666,  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
		$user3 = App\Exam::create( 
			['name' => 'TintuExam',  'apply_date' => 777, 'exam_date' => 888,  'created_at' => new DateTime(), 'updated_at' => new DateTime()] );
	}

}
