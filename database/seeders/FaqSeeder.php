<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'id' => 1,
                'question' => 'Who are the Clergies?',
                'answer' => "Clergies are trusted spiritual mentors and advisors who provide guidance, support, and prayers to help individuals grow in their faith and overcome personal challenges. They are experienced in offering spiritual counseling and compassionate listening."
            ],
            [
                'id' => 2,
                'question' => 'Who will be helping me?',
                'answer' => "You will be connected with a dedicated Clergy who is trained to support people through spiritual guidance, encouragement, and meaningful conversations. They will help you navigate your concerns and strengthen your faith journey."
            ],
            [
                'id' => 3,
                'question' => 'Is Faithlink right for me?',
                'answer' => "Faithlink is designed for anyone seeking spiritual guidance, emotional support, or someone to talk to about life’s challenges from a faith-based perspective. If you want a supportive and confidential environment to grow spiritually, Faithlink may be a good fit for you."
            ],
            [
                'id' => 4,
                'question' => 'How will I communicate with my Clergy?',
                'answer' => "You will communicate with your Clergy through the Faithlink platform. This may include secure messaging or chat features provided within the service."
            ],
            [
                'id' => 5,
                'question' => 'How long can I use Faithlink?',
                'answer' => "You can use Faithlink for as long as your membership or access remains active. The duration may depend on the subscription plan or service option you choose."
            ],
            [
                'id' => 6,
                'question' => 'What payment methods do you accept?',
                'answer' => "We accept all major credit and debit cards, including:\n• Visa\n• Mastercard\n• American Express\n• Discover\nPayments are processed securely via Stripe. You’ll receive a receipt by email for each billing cycle, and you can manage your subscription anytime from your account."
            ],
        ];

        DB::table('faqs')->insert($faqs);
    }
}
