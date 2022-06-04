<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Integer;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int|null $profile_media_id
 * @property string|null $name
 * @property string $email
 * @property string $role
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Model
{
    use HasFactory, Notifiable;

    protected $hidden = ['email'];



    public static function validateStaff($_email, Item $_item, $_item_id){
        $user = User::whereEmail($_email )->firstOrFail();
//        $user_orgs = UserOrganization::all()->reject(function ($user1) use ($user) {
//            return $user1->user_id != $user->id;
//        });
        $user_orgs = UserOrganization::all()->where('user_id', $user->id);
//        print_r( $user_orgs->count() ) ;
//        dd();
        $user_in_org = false;
        foreach (   $user_orgs  as $user_org) {
//            echo  $_item->organization_id . ', ' ;
            if($user_org->organization_id == $_item->organization_id){
                $user_in_org = true;
//                echo $_item_id;
            }
//            echo $user_org->organization_id;
        }
//        dd();
// check user is part of that org
        if (!$user_in_org) {
            return [
                'status_code' => 0,
                'message' => 'you are not part of this item\'s organization lol'
            ];
        }

        // check user is chef
//        if ($user_org->role !== 'chef' ) {
//            return [
//                'status_code' => 0,
//                'message' => 'you are not a chef in this organization lol'
//            ];
//        }

    }


    public static function validateItemOrganization( int $_item_id, int $_organization_id){
        $item = Item::whereId($_item_id)->firstOrFail();
        $org = Organization::whereId($_organization_id)->firstOrFail();

        dd($item);

        $user = User::whereEmail($_email )->firstOrFail();
//        $user_orgs = UserOrganization::all()->reject(function ($user1) use ($user) {
//            return $user1->user_id != $user->id;
//        });
        $user_orgs = UserOrganization::all()->where('user_id', $user->id);
//        print_r( $user_orgs->count() ) ;
//        dd();
        $user_in_org = false;
        foreach (   $user_orgs  as $user_org) {
//            echo  $_item->organization_id . ', ' ;
            if($user_org->organization_id == $_item->organization_id){
                $user_in_org = true;
//                echo $_item_id;
            }
//            echo $user_org->organization_id;
        }
//        dd();
// check user is part of that org
        if (!$user_in_org) {
            return [
                'status_code' => 0,
                'message' => 'you are not part of this item\'s organization lol'
            ];
        }

        // check user is chef
//        if ($user_org->role !== 'chef' ) {
//            return [
//                'status_code' => 0,
//                'message' => 'you are not a chef in this organization lol'
//            ];
//        }

    }

    public static function validateUserOrganization( int $_user_id, int $_organization_id){
//        $user = User::whereEmail($_email )->firstOrFail();
//        $user_orgs = UserOrganization::all()->reject(function ($user1) use ($user) {
//            return $user1->user_id != $user->id;
//        });
        $user_orgs = UserOrganization::all()->where('user_id', $_user_id);
//        print_r( $user_orgs->count() ) ;
//        dd();
        $user_in_org = false;
        foreach (   $user_orgs  as $user_org) {
//            echo  $_item->organization_id . ', ' ;
            if($user_org->organization_id == $_organization_id){
                $user_in_org = true;
//                echo $_item_id;
            }
//            echo $user_org->organization_id;
        }
//        dd();
// check user is part of that org
        if (!$user_in_org) {
            return [
                'status_code' => 0,
                'message' => 'you are not part of this organization lol'
            ];
        }

        // check user is chef
//        if ($user_org->role !== 'chef' ) {
//            return [
//                'status_code' => 0,
//                'message' => 'you are not a chef in this organization lol'
//            ];
//        }

    }



    public static function validateUserOrganizationRole( int $_user_id, int $_org_id,  array $_roles){
//        $user = User::whereEmail($email )->firstOrFail();

        // validate user organization
        $no_access = User::validateUserOrganization( $_user_id,  $_org_id );
        if ($no_access) {
            return $no_access;
        }

        $user_org = UserOrganization::whereUserId( $_user_id)->whereOrganizationId($_org_id) ->first();

//            dd($user_org);
// check user is part of that org
//        if (!$user_org) {
//            return [
//                'status_code' => 0,
//                'message' => 'you dont have the role "'.$_role.'" for this lol'
//            ];
//        }

        foreach (   $_roles  as $role) {
//            echo  $_item->organization_id . ', ' ;
            if($user_org->role == $role){
                $user_in_org = true;
//                echo $_item_id;
                return [
                    'status_code' => 0,
                    'message' => 'you dont have the role "'.$_role.'" for this lol'
                ];
            }
//            echo $user_org->organization_id;
        }

        // check user is chef
//        if ($user_org->role !== 'chef' ) {
//            return [
//                'status_code' => 0,
//                'message' => 'you are not a chef in this organization lol'
//            ];
//        }

    }


   public static function validateChef($email, $organization_id){
       $user = User::whereEmail($email )->firstOrFail();
       $user_org = UserOrganization::where('user_id', $user->id,)->where('organization_id', $organization_id)->first();

// check user is part of that org
       if (!$user_org) {
           return [
               'status_code' => 0,
               'message' => 'you are not part of this organization lol'
           ];
       }

       // check user is chef
       if ($user_org->role !== 'chef' ) {
           return [
               'status_code' => 0,
               'message' => 'you are not a chef in this organization lol'
           ];
       }

}




}
