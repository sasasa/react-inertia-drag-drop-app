<?php
namespace App\UseCases;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UsersUpdateRequest;
use App\Models\User;

class UsersMultiUpadateUseCase
{
    /**
     * @param UsersRequest $request
     * @return void
     * @throws ValidationException
     */
    public function __invoke(array $users): void
    {
        try{
            DB::beginTransaction();
            $ids = collect();
            $stopIndex = null;
            collect($users)->each(function($user, $index) use(&$ids, &$stopIndex){
                $stopIndex = $index;
                if($user['id']) {
                    $u = User::findOrFail($user['id']);
                    $u->name = $user['name'];
                    $u->username = $user['username'];
                    $u->email = $user['email'];
                    $u->email_verified_at = $user['is_email_verified'] ? now() : null;
                    $u->save();
                } else {
                    $u = User::create([
                        "name" => $user['name'],
                        "username" => $user['username'],
                        "email" => $user['email'],
                        'password' => Hash::make('test'),
                        'email_verified_at' => ($user['is_email_verified'] ? now() : null),
                    ]);
                }
                $ids->push($u->id);
            });
            User::setNewOrder($ids);
            DB::commit();
        } catch (QueryException $e) {
            Log::warning($e->getMessage());
            DB::rollBack();
            throw ValidationException::withMessages([
                "users.{$stopIndex}.email" => 'Emailの値は既に存在しています。',
            ]);
        }
    }
}