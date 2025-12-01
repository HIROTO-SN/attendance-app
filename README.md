新規 Laravel プロジェクト作成

パッケージ導入（Filament, Livewire, Sanctum, Spatie など）


DB マイグレーション（主要テーブル）

Eloquent モデルとリレーション

Filament Resources（管理者用 CRUD）

Livewire コンポーネント（打刻・申請UI）

API と認証（Sanctum）

承認ワークフロー・通知（メール/Slack）

集計・レポート（コマンドとスケジューラ）

セキュリティ（IP制限/MFA/監査ログ）

追加機能の実装ポイント（GPS/顔認証/端末固定）

デプロイ・運用メモ

1) 新規プロジェクト作成（コマンド）
# Laravel プロジェクト作成
composer create-project laravel/laravel attendance-app
composer create-project laravel/laravel kintai-app

cd attendance-app

# Git 初期化
git init
git add .
git commit -m "initial laravel"

# Node & Tailwind (Vite)
npm install
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p

# .env で DB 設定を行う（DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD）

2) 必要パッケージ導入（コマンド）
# 認証スキャフォールド（Breeze の Livewire 版を推奨）
composer require laravel/breeze --dev
php artisan breeze:install livewire
npm install && npm run dev
php artisan migrate

# Filament (管理画面)
composer require filament/filament
php artisan vendor:publish --tag=filament-config
php artisan make:filament-user AdminUser

# Livewire（既に Breeze で入るはず）
composer require livewire/livewire

# Sanctum（APIトークン）
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# Spatie Permission（ロール/権限）
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# Shield プラグイン
composer require bezhansalleh/filament-shield
php artisan vendor:publish --tag=filament-shield-config

php artisan shield:install

php artisan shield:super-admin で好きなユーザーにアドミンロールを付与できる


# CSV/PDF 等
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf

# Slack 通知
composer require laravel/slack-notification-channel

# Optional: Geo / QR / Face SDK を後で導入

3) マイグレーション（主要テーブル）

以下は主要なマイグレーションの概略コード。database/migrations/xxxx_create_*.php に置く想定。

users（既存の users にカラム追加）
php artisan make:migration add_employee_fields_to_users --table=users
php artisan make:migration create_departments_table
php artisan make:migration create_attendance_records_table
php artisan make:migration create_leave_requests_table
php artisan make:migration create_shifts_table
php artisan make:migration create_holiday_calendars_table

php artisan make:model Departments


// database/migrations/xxxx_add_employee_fields_to_users.php
public function up() {
    Schema::table('users', function (Blueprint $table) {
        $table->string('employee_code')->nullable()->unique();
        $table->foreignId('department_id')->nullable()->constrained('departments');
        $table->string('position')->nullable();
        $table->enum('employment_type', ['fulltime','parttime','contract'])->default('fulltime');
        $table->date('join_date')->nullable();
        $table->date('leave_date')->nullable();
        $table->boolean('is_admin')->default(false);
    });
}

departments
Schema::create('departments', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});

attendance_records
Schema::create('attendance_records', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->date('work_date');
    $table->timestamp('clock_in')->nullable();
    $table->timestamp('clock_out')->nullable();
    $table->timestamp('break_start')->nullable();
    $table->timestamp('break_end')->nullable();
    $table->integer('break_minutes')->default(0);
    $table->integer('working_minutes')->default(0);
    $table->integer('overtime_minutes')->default(0);
    $table->enum('status', ['working','finished','absent','holiday','late','early_leave'])->default('working');
    $table->text('note')->nullable();
    $table->json('metadata')->nullable(); // GPS, IP など保存用
    $table->timestamps();

    $table->unique(['user_id','work_date']);
});

leave_requests
Schema::create('leave_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->enum('type', ['paid','unpaid','special','substitute']);
    $table->date('start_date');
    $table->date('end_date');
    $table->time('start_time')->nullable();
    $table->time('end_time')->nullable();
    $table->text('reason')->nullable();
    $table->enum('status', ['pending','approved','rejected'])->default('pending');
    $table->foreignId('approver_id')->nullable()->constrained('users');
    $table->timestamps();
});

shifts
Schema::create('shifts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->date('shift_date');
    $table->time('start_time')->nullable();
    $table->time('end_time')->nullable();
    $table->integer('break_minutes')->default(0);
    $table->timestamps();
});

holiday_calendars
Schema::create('holiday_calendars', function (Blueprint $table) {
    $table->id();
    $table->date('holiday_date')->unique();
    $table->string('description')->nullable();
    $table->timestamps();
});


マイグレーションを実行：

php artisan migrate

4) モデルとリレーション（主要部分）
User.php（抜粋）
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasFactory, Notifiable, HasRoles;

    public function department() { return $this->belongsTo(Department::class); }
    public function attendanceRecords() { return $this->hasMany(AttendanceRecord::class); }
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class); }
    public function shifts() { return $this->hasMany(Shift::class); }
}

AttendanceRecord.php
class AttendanceRecord extends Model {
    protected $fillable = ['user_id','work_date','clock_in','clock_out','break_start','break_end','break_minutes','working_minutes','overtime_minutes','status','note','metadata'];

    protected $casts = [
        'work_date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(){ return $this->belongsTo(User::class); }

    // 実働・残業計算ユーティリティ
    public function calculateTimes($companyRules) {
        // 簡易例: 実働分・休憩分・残業を計算してフィールドにセットするロジック
        if($this->clock_in && $this->clock_out) {
            $minutes = $this->clock_out->diffInMinutes($this->clock_in);
            // 休憩の自動挿入ロジック（例）
            $break = 0;
            if($minutes > 8*60) $break = 60;
            elseif($minutes > 6*60) $break = 45;
            $this->break_minutes = $break;
            $this->working_minutes = max(0, $minutes - $break);
            // 残業は所定労働時間を超えた分（簡易）
            $scheduledMinutes = ($companyRules['scheduled_minutes'] ?? 8*60);
            $this->overtime_minutes = max(0, $this->working_minutes - $scheduledMinutes);
            $this->save();
        }
    }
}

5) Filament Resources（管理画面 CRUD）

Filament 用リソースを作成して管理者が CRUD 操作できるようにします。

php artisan make:filament-resource Department
php artisan make:filament-resource User
php artisan make:filament-resource AttendanceRecord
php artisan make:filament-resource LeaveRequest
php artisan make:filament-resource Shift
php artisan make:filament-resource HolidayCalendar


各 Resource の Form と Table に必要なカラムを設定。例：AttendanceRecordResource.php の Table には user.name, work_date, clock_in, clock_out, status など表示。

Filament のカスタムページで「月次集計」や「打刻修正履歴」を作成可能。

6) Livewire コンポーネント — 打刻（Clock）サンプル

php artisan make:livewire ClockInOut で作成し、resources/views/livewire/clock-in-out.blade.php と app/Http/Livewire/ClockInOut.php に実装。

Livewire コンポーネント（抜粋）
class ClockInOut extends Component {
    public $user;
    public $message;

    public function mount() {
        $this->user = auth()->user();
    }

    public function clockIn() {
        $today = now()->toDateString();
        $record = AttendanceRecord::firstOrCreate(
            ['user_id'=>$this->user->id, 'work_date'=>$today],
            ['clock_in'=>now()]
        );
        if(!$record->clock_in) { $record->clock_in = now(); $record->save(); }
        $record->metadata = array_merge($record->metadata ?? [], [
            'ip' => request()->ip(),
            'clock_in_gps' => request('gps') // from frontend
        ]);
        $record->save();
        $this->message = '出勤を記録しました: '.now();
    }

    public function clockOut() {
        $today = now()->toDateString();
        $record = AttendanceRecord::firstWhere(['user_id'=>$this->user->id, 'work_date'=>$today]);
        if(!$record) { $this->message = '出勤記録が見つかりません'; return; }
        $record->clock_out = now();
        $record->save();
        $record->calculateTimes(['scheduled_minutes'=>8*60]);
        $this->message = '退勤を記録しました: '.now();
    }

    public function render() { return view('livewire.clock-in-out'); }
}


フロント側はボタン「出勤」「退勤」、GPS や QR を拾う JavaScript を追加。

7) API / 認証（Sanctum） & ルート例

routes/api.php に API を作成し、モバイルや外部端末の打刻を受ける。

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/clock-in', [Api\AttendanceController::class,'clockIn']);
    Route::post('/clock-out', [Api\AttendanceController::class,'clockOut']);
    Route::get('/my-attendance', [Api\AttendanceController::class,'myAttendance']);
});


AttendanceController@clockIn 内で IP 制限や GPS のチェック、QR/顔認証結果の検証などを行います。

8) 承認ワークフロー・通知

申請（LeaveRequest）作成 → status='pending' → 上長（approver）へ通知

通知は Laravel Notification を利用（メール / Slack / DB）

Notification サンプル
class LeaveRequested extends Notification {
    public function via($notifiable) { return ['mail','database','slack']; }

    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject('休暇申請の承認依頼')
            ->line('申請があります。')
            ->action('承認する', url('/filament/resources/leave-requests'));
    }

    public function toSlack($notifiable){
        return (new SlackMessage)->content("{$notifiable->name}さんに休暇承認が必要です");
    }
}


申請作成時に $approver->notify(new LeaveRequested($leaveRequest));

多段階承認は approver_id を配列化（承認チェーン）するか、approvals テーブルで管理。

9) 集計・レポート（コマンド & スケジューラ）

月次計算は Artisan コマンド化して Scheduler へ登録。

php artisan make:command MonthlyAttendanceSummary


コマンド内で AttendanceRecord::whereBetween('work_date', [..])->groupBy('user_id')->selectRaw(...) を使い、CSV/PDF を生成してストレージへ保存 or Slack に通知。

スケジューラ登録：

// app/Console/Kernel.php
protected function schedule(Schedule $schedule){
    $schedule->command('attendance:monthly-summary')->monthlyOn(1,'01:00');
}

10) セキュリティと監査

RBAC：spatie/permission で roles（admin, manager, staff）と permissions を割当

IP 制限：ミドルウェア作成（CheckIpForClock）で request()->ip() を確認

MFA：Laravel Fortify / Google Authenticator を導入可能

監査ログ：spatie/laravel-activitylog を導入して「誰がいつ勤怠を修正したか」を記録

11) 高度機能の実装ポイント（短く）

GPS 打刻：フロントで navigator.geolocation で位置を取得し、API へ送信。サーバで許可範囲チェック。

QR 打刻：管理画面で QR を生成し、モバイルでスキャンして API に JWT もしくは一時トークンを送信。

顔認証：外部 SDK（AWS Rekognition / Face++ / Azure Face）を利用。個人画像を同意のもと登録、API 経由で照合。

端末固定：端末ID（UUID）を登録して比較、または IP 制限。

※ 顔認証等はプライバシー法・同意管理が必須。実装前に法務チェックを推奨。

12) デプロイ / 運用

.env のメール / Slack / DB 設定を本番向けに。

Supervisor で queue:work を監視（通知ジョブ等）。

cron をセット（Laravel scheduler を稼働）。

バックアップ（DB スナップ、S3 にアップロード）。

ログ（storage/logs）や監査ログのローテート。

付録：実務で使える便利なコマンドまとめ
# Filament resource 作成例（既出）
php artisan make:filament-resource AttendanceRecord

# Livewire 作成
php artisan make:livewire ClockInOut

# Notification 作成
php artisan make:notification LeaveRequested

# Artisan コマンド
php artisan make:command MonthlyAttendanceSummary

# Policy 作成（編集権限等）
php artisan make:policy AttendanceRecordPolicy --model=AttendanceRecord

提案：今すぐ渡せる追加成果物（私が作成できます）

完全な マイグレーションファイル群（上のスケルトンをフル実装）

Filament Resource の フル実装コード（一覧・編集・フィルタ・エクスポート）

Livewire の 打刻 UI（HTML + JS） と API コントローラ

月次集計コマンド + サンプル CSV/PDF 出力コード

デプロイ用 Dockerfile と docker-compose.yml（必要なら）

どれから作りましょうか？（たとえば「まずマイグレーションとモデルの完全実装」→ 私がそのファイル群を生成します）

必要なものを指定していただければ、そのファイル群（コード）をここで全部提示します。