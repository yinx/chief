<?php

namespace Chief\Users\Invites\Application;

use Chief\Users\Invites\Events\UserInvited;
use Chief\Users\Invites\Invitation;
use Chief\Users\User;
use Chief\Users\Invites\InvitationState;
use Illuminate\Support\Facades\DB;
use Chief\Common\State\StateException;

class InviteUser
{
    public function handle(User $invitee, User $inviter)
    {
        try {
            DB::beginTransaction();

            $invitation = Invitation::make($invitee->id, $inviter->id);

            (new InvitationState($invitation))->apply('invite');

            event(new UserInvited($invitation->id));

            DB::commit();

            return;

        } catch (StateException $e) {
            // exception is thrown if state transfer is already done
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}