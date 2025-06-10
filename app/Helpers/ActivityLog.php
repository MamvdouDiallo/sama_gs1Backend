<?php
namespace App\Helpers;

use App\Models\ActivityLog as ModelsActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request as FacadesRequest;
class ActivityLog
{

    public function createLog($description,$before=null,$after=null)
    {
        $user=auth()->check();
        $logs=[];
        $logs['description']=$description;
        $logs['url']=FacadesRequest::url();
        $logs['methode']=FacadesRequest::method();
        $logs['ip']=FacadesRequest::ip();
        $logs['agent']=Str::limit(FacadesRequest::header('user-agent'),100);
        $before['before']=$before;
        $logs['after']=$after;
        $logs['user_id']=$user ? auth()->user()->id : 0;
        $logs['user_email']=$user ? auth()->user()->email : 'not a person';
        $logs['user_role']=$user ? auth()->user()->role :'service';
        ModelsActivityLog::create($logs);
    }
}









?>
