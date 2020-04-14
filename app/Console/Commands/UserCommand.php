<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Message;
use App\Models\Observer;
use App\Models\Ticket;
use App\Models\UserExtraField;
use App\Models\UserExtraFieldValue;
use App\Models\UserExtraTextField;
use App\Models\UserTicketAccess;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Your Best User';
    /* @var Generator */
    private $faker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $cpf = UserExtraField::where('name', 'CPF')->first();
        $nascimento = UserExtraField::where('name','Nascimento')->first();

        DB::beginTransaction();
        $user = new User;
        $user->is_admin = $this->adminQuestion();
        $user->name = $this->ask("What's the name of the user? (Ex.: Jhon Doe)");
        $user->email = $this->ask("Whats your email? (Ex.: user@provider.com)");
        $user->password = Hash::make( $this->secret("Type the password of the user:") );
        $user->email_verified_at = Carbon::now();
        $user->department_id = $this->departmentQuestion();
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();
        $user->should_display = $this->displayQuestion();
        $user->save();

        $userCpf = new UserExtraFieldValue;
        $userCpf->user_id = $user->id;
        $userCpf->user_extra_field_id = $cpf->id;
        $userCpf->value = $this->cpfQuestion();
        $userCpf->save();

        $userNascimento = new UserExtraFieldValue;
        $userNascimento->user_id = $user->id;
        $userNascimento->user_extra_field_id = $nascimento->id;
        $userNascimento->value = $this->nascimentoQuestion();
        $userNascimento->save();

        DB::commit();

    }

    private function adminQuestion() : Bool {
        $admin = $this->ask("Is this user an admin? (no/yes) - Blank: no");
        switch ($admin) {
            case "":
            case "no":
                return false;
            case "yes":
                return true;
            default:
                return $this->adminQuestion();
        }
    }

    private function departmentQuestion() : Int {
        $departments = Department::all();
        $departmentsText = "";
        $departmentIds = [];
        foreach ($departments as $department) {
            $departmentsText .= "\n\t{$department->id} - {$department->name}";
            $departmentIds[] = $department->id;
        }


        $department = $this->ask("What is your department? {$departmentsText}");

        if ( in_array($department, $departmentIds) ) {
            return $department;
        }
        return $this->departmentQuestion();
    }

    private function displayQuestion() : Bool {
        $admin = $this->ask("Is this user should appear as target of tickets? (yes/no) - Blank: yes");
        switch ($admin) {
            case "no":
                return false;
            case "":
            case "yes":
                return true;
            default:
                return $this->displayQuestion();
        }
    }

    private function cpfQuestion() : String {
        $cpf = $this->ask("Digite o CPF: (Formato: 000.000.000-00)");
        if ( !preg_match("/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$/", $cpf) ) {
            return $this->cpfQuestion();
        }

        return $cpf;
    }

    private function nascimentoQuestion() : String {
        $nascimento = $this->ask("Digite a data de nascimento: (Formato: 00/00/0000)");
        if ( !preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $nascimento) ) {
            return $this->nascimentoQuestion();
        }

        return $nascimento;
    }
}
