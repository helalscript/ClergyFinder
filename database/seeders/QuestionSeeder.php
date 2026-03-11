<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Questions
        DB::table('questions')->insert([
            ['id' => '3', 'user_type' => 'clergy', 'question' => 'Welcome! What brings you to clergy service?', 'image' => null, 'answer_type' => 'single_choice', 'answer' => null, 'sort_order' => '2', 'status' => 'active', 'created_at' => '2026-03-11 10:52:16', 'updated_at' => '2026-03-11 10:56:05'],
            ['id' => '4', 'user_type' => 'clergy', 'question' => 'Which best describes your ministry experience?', 'image' => null, 'answer_type' => 'single_choice', 'answer' => null, 'sort_order' => '3', 'status' => 'active', 'created_at' => '2026-03-11 10:53:05', 'updated_at' => '2026-03-11 10:55:57'],
            ['id' => '5', 'user_type' => 'clergy', 'question' => 'What is your denominational background?', 'image' => null, 'answer_type' => 'single_choice', 'answer' => null, 'sort_order' => '4', 'status' => 'active', 'created_at' => '2026-03-11 10:53:47', 'updated_at' => '2026-03-11 10:55:43'],
            ['id' => '6', 'user_type' => 'clergy', 'question' => 'What is your preferred time commitment?', 'image' => null, 'answer_type' => 'single_choice', 'answer' => null, 'sort_order' => '4', 'status' => 'active', 'created_at' => '2026-03-11 10:54:34', 'updated_at' => '2026-03-11 10:56:27'],
            ['id' => '7', 'user_type' => 'clergy', 'question' => 'Are you interested in this?', 'image' => 'uploads/question_page/1773204934-1773204934-frame-2147241689.png', 'answer_type' => 'yes_no', 'answer' => null, 'sort_order' => '5', 'status' => 'active', 'created_at' => '2026-03-11 10:55:34', 'updated_at' => '2026-03-11 10:55:34'],
            ['id' => '8', 'user_type' => 'seeker', 'question' => 'Hello friend! What brings you to Faithlink?', 'image' => null, 'answer_type' => 'single_choice', 'answer' => null, 'sort_order' => '0', 'status' => 'active', 'created_at' => '2026-03-11 11:16:05', 'updated_at' => '2026-03-11 11:16:05'],
            ['id' => '9', 'user_type' => 'seeker', 'question' => 'Which of the best describe your experience with prayer?', 'image' => null, 'answer_type' => 'single_choice', 'answer' => null, 'sort_order' => '1', 'status' => 'active', 'created_at' => '2026-03-11 11:16:53', 'updated_at' => '2026-03-11 11:19:07'],
            ['id' => '10', 'user_type' => 'seeker', 'question' => 'Are you interested in this?', 'image' => 'uploads/question_page/1773206248-1773206248-frame-2147241689-1.png', 'answer_type' => 'yes_no', 'answer' => null, 'sort_order' => '2', 'status' => 'active', 'created_at' => '2026-03-11 11:17:28', 'updated_at' => '2026-03-11 11:17:28'],
            ['id' => '11', 'user_type' => 'seeker', 'question' => 'Are you interested in this?', 'image' => 'uploads/question_page/1773206277-1773206277-frame-2147241689-2.png', 'answer_type' => 'yes_no', 'answer' => null, 'sort_order' => '3', 'status' => 'active', 'created_at' => '2026-03-11 11:17:58', 'updated_at' => '2026-03-11 11:17:58'],
            ['id' => '12', 'user_type' => 'seeker', 'question' => 'Are you interested in this?', 'image' => 'uploads/question_page/1773206326-1773206326-frame-2147241686.png', 'answer_type' => 'yes_no', 'answer' => null, 'sort_order' => '4', 'status' => 'active', 'created_at' => '2026-03-11 11:18:46', 'updated_at' => '2026-03-11 11:18:46'],
        ]);
        // Question Options
        DB::table('question_options')->insert([
            ['id' => '23', 'question_id' => '5', 'option_text' => 'Protestant', 'sort_order' => '0', 'created_at' => '2026-03-11 10:55:43', 'updated_at' => '2026-03-11 10:55:43'],
            ['id' => '24', 'question_id' => '5', 'option_text' => 'Catholic', 'sort_order' => '1', 'created_at' => '2026-03-11 10:55:43', 'updated_at' => '2026-03-11 10:55:43'],
            ['id' => '25', 'question_id' => '5', 'option_text' => 'Orthodox', 'sort_order' => '2', 'created_at' => '2026-03-11 10:55:43', 'updated_at' => '2026-03-11 10:55:43'],
            ['id' => '26', 'question_id' => '5', 'option_text' => 'Non-denominational', 'sort_order' => '3', 'created_at' => '2026-03-11 10:55:43', 'updated_at' => '2026-03-11 10:55:43'],
            ['id' => '27', 'question_id' => '5', 'option_text' => 'Other', 'sort_order' => '4', 'created_at' => '2026-03-11 10:55:43', 'updated_at' => '2026-03-11 10:55:43'],
            ['id' => '28', 'question_id' => '4', 'option_text' => 'Exploring ministry', 'sort_order' => '0', 'created_at' => '2026-03-11 10:55:57', 'updated_at' => '2026-03-11 10:55:57'],
            ['id' => '29', 'question_id' => '4', 'option_text' => 'Some theological training', 'sort_order' => '1', 'created_at' => '2026-03-11 10:55:57', 'updated_at' => '2026-03-11 10:55:57'],
            ['id' => '30', 'question_id' => '4', 'option_text' => 'Seminary graduate', 'sort_order' => '2', 'created_at' => '2026-03-11 10:55:57', 'updated_at' => '2026-03-11 10:55:57'],
            ['id' => '31', 'question_id' => '4', 'option_text' => 'Ordained clergy', 'sort_order' => '3', 'created_at' => '2026-03-11 10:55:57', 'updated_at' => '2026-03-11 10:55:57'],
            ['id' => '32', 'question_id' => '4', 'option_text' => 'Experienced pastor', 'sort_order' => '4', 'created_at' => '2026-03-11 10:55:57', 'updated_at' => '2026-03-11 10:55:57'],
            ['id' => '33', 'question_id' => '3', 'option_text' => 'Serve my community', 'sort_order' => '0', 'created_at' => '2026-03-11 10:56:05', 'updated_at' => '2026-03-11 10:56:05'],
            ['id' => '34', 'question_id' => '3', 'option_text' => 'Answer God\'s calling', 'sort_order' => '1', 'created_at' => '2026-03-11 10:56:05', 'updated_at' => '2026-03-11 10:56:05'],
            ['id' => '35', 'question_id' => '3', 'option_text' => 'Lead spiritual growth', 'sort_order' => '2', 'created_at' => '2026-03-11 10:56:05', 'updated_at' => '2026-03-11 10:56:05'],
            ['id' => '36', 'question_id' => '3', 'option_text' => 'Provide pastoral care', 'sort_order' => '3', 'created_at' => '2026-03-11 10:56:05', 'updated_at' => '2026-03-11 10:56:05'],
            ['id' => '37', 'question_id' => '3', 'option_text' => 'Other', 'sort_order' => '4', 'created_at' => '2026-03-11 10:56:05', 'updated_at' => '2026-03-11 10:56:05'],
            ['id' => '38', 'question_id' => '6', 'option_text' => 'Part-time (10-20 hrs/wk)', 'sort_order' => '0', 'created_at' => '2026-03-11 10:56:27', 'updated_at' => '2026-03-11 10:56:27'],
            ['id' => '39', 'question_id' => '6', 'option_text' => 'Half-time (20-30 hrs/wk)', 'sort_order' => '1', 'created_at' => '2026-03-11 10:56:27', 'updated_at' => '2026-03-11 10:56:27'],
            ['id' => '40', 'question_id' => '6', 'option_text' => 'Full-time (30-40 hrs/wk)', 'sort_order' => '2', 'created_at' => '2026-03-11 10:56:27', 'updated_at' => '2026-03-11 10:56:27'],
            ['id' => '41', 'question_id' => '6', 'option_text' => 'Bi-vocational', 'sort_order' => '3', 'created_at' => '2026-03-11 10:56:27', 'updated_at' => '2026-03-11 10:56:27'],
            ['id' => '42', 'question_id' => '6', 'option_text' => 'Flexible availability', 'sort_order' => '4', 'created_at' => '2026-03-11 10:56:27', 'updated_at' => '2026-03-11 10:56:27'],
            ['id' => '43', 'question_id' => '8', 'option_text' => 'Build a prayer habit', 'sort_order' => '0', 'created_at' => '2026-03-11 11:16:05', 'updated_at' => '2026-03-11 11:16:05'],
            ['id' => '44', 'question_id' => '8', 'option_text' => 'Reduce stress and anxiety', 'sort_order' => '1', 'created_at' => '2026-03-11 11:16:05', 'updated_at' => '2026-03-11 11:16:05'],
            ['id' => '45', 'question_id' => '8', 'option_text' => 'Grow closer to God', 'sort_order' => '2', 'created_at' => '2026-03-11 11:16:05', 'updated_at' => '2026-03-11 11:16:05'],
            ['id' => '46', 'question_id' => '8', 'option_text' => 'Sleep better', 'sort_order' => '3', 'created_at' => '2026-03-11 11:16:05', 'updated_at' => '2026-03-11 11:16:05'],
            ['id' => '47', 'question_id' => '8', 'option_text' => 'Other', 'sort_order' => '4', 'created_at' => '2026-03-11 11:16:05', 'updated_at' => '2026-03-11 11:16:05'],
            ['id' => '53', 'question_id' => '9', 'option_text' => 'I haven’t prayed much before', 'sort_order' => '0', 'created_at' => '2026-03-11 11:19:07', 'updated_at' => '2026-03-11 11:19:07'],
            ['id' => '54', 'question_id' => '9', 'option_text' => 'I don’t get much of it', 'sort_order' => '1', 'created_at' => '2026-03-11 11:19:07', 'updated_at' => '2026-03-11 11:19:07'],
            ['id' => '55', 'question_id' => '9', 'option_text' => 'I pray regularly', 'sort_order' => '2', 'created_at' => '2026-03-11 11:19:07', 'updated_at' => '2026-03-11 11:19:07'],
            ['id' => '56', 'question_id' => '9', 'option_text' => 'I have a daily routine', 'sort_order' => '3', 'created_at' => '2026-03-11 11:19:07', 'updated_at' => '2026-03-11 11:19:07'],
            ['id' => '57', 'question_id' => '9', 'option_text' => 'I pray without ceasing', 'sort_order' => '4', 'created_at' => '2026-03-11 11:19:07', 'updated_at' => '2026-03-11 11:19:07'],
        ]);
    }
}
