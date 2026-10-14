<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * デモ用データ（架空の顧客5件＋タスク）を投入します。
 * 何度実行しても同じ結果になるよう、デモユーザーの顧客は毎回作り直します。
 *
 * 実行: php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    // クライアントに共有するログイン情報（本番公開前に必ず変更・削除してください）
    private const DEMO_EMAIL    = 'demo@example.com';
    private const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => self::DEMO_EMAIL],
            [
                'name'              => 'デモ太郎',
                'password'          => self::DEMO_PASSWORD, // Userモデルのhashedキャストで自動ハッシュ化
                'email_verified_at' => now(),
            ]
        );

        // 再実行時の重複防止（顧客削除に伴いタスクもcascadeで削除される）
        $user->customers()->delete();

        foreach ($this->customers() as $data) {
            $tasks = $data['tasks'];
            unset($data['tasks']);

            $customer = $user->customers()->create($data);

            foreach ($tasks as [$content, $dueInDays, $status]) {
                $customer->tasks()->create([
                    'content'  => $content,
                    'due_date' => $dueInDays === null ? null : now()->addDays($dueInDays)->toDateString(),
                    'status'   => $status,
                ]);
            }
        }
    }

    /**
     * タスクは [内容, 期限（今日からの日数 / null=未設定）, ステータス]
     * 期限は実行日基準なので、いつデプロイしても「期限切れ」「今週中」が混在して見えます。
     */
    private function customers(): array
    {
        return [
            [
                'name'         => '田中 健一',
                'company_name' => '株式会社サクラテック',
                'email'        => 'tanaka@sakura-tech.example.com',
                'phone'        => '03-1234-5601',
                'tasks'        => [
                    ['サービス導入の見積書を作成して送付する', -2, TaskStatus::InProgress], // 期限切れ
                    ['キックオフミーティングの日程を調整する', 3, TaskStatus::Todo],
                    ['要件ヒアリングの議事録を共有する', -5, TaskStatus::Done],
                    ['契約書ドラフトの法務確認を依頼する', 10, TaskStatus::Todo],
                ],
            ],
            [
                'name'         => '佐藤 美咲',
                'company_name' => '北斗フーズ株式会社',
                'email'        => 'sato@hokuto-foods.example.com',
                'phone'        => '03-1234-5602',
                'tasks'        => [
                    ['ECサイトのリニューアル提案書を作成する', 5, TaskStatus::InProgress],
                    ['現行サイトのアクセス解析レポートを提出する', -1, TaskStatus::Todo], // 期限切れ
                    ['商品写真の撮影スケジュールを確認する', 14, TaskStatus::Todo],
                ],
            ],
            [
                'name'         => '鈴木 大輔',
                'company_name' => '合同会社ミライデザイン',
                'email'        => 'suzuki@mirai-design.example.com',
                'phone'        => '03-1234-5603',
                'tasks'        => [
                    ['ロゴデザイン案（3パターン）を送付する', -7, TaskStatus::Done],
                    ['ロゴ修正依頼の内容を整理して返信する', 1, TaskStatus::InProgress],
                    ['名刺・封筒のデザイン見積もりを作成する', 7, TaskStatus::Todo],
                    ['請求書を発行する', 21, TaskStatus::Todo],
                ],
            ],
            [
                'name'         => '高橋 由美',
                'company_name' => '東光物流株式会社',
                'email'        => 'takahashi@toko-logistics.example.com',
                'phone'        => '03-1234-5604',
                'tasks'        => [
                    ['配送管理システムの運用マニュアルを納品する', -10, TaskStatus::Done],
                    ['システム障害の問い合わせに回答する', 0, TaskStatus::InProgress], // 本日期限
                    ['保守契約の更新案内を送付する', 30, TaskStatus::Todo],
                ],
            ],
            [
                'name'         => '伊藤 翔太',
                'company_name' => '株式会社ブルーリーフ',
                'email'        => 'ito@blueleaf.example.com',
                'phone'        => '03-1234-5605',
                'tasks'        => [
                    ['初回ヒアリングのお礼メールを送る', -3, TaskStatus::Done],
                    ['業務効率化の概算スケジュールを作成する', 4, TaskStatus::InProgress],
                    ['社内会議用の資料を準備する', null, TaskStatus::Todo], // 期限なし
                ],
            ],
        ];
    }
}