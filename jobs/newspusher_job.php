<?php
namespace Concrete\Package\NewspushMaster\Job;

use Concrete\Package\NewspushMaster\Push;
use Job;

final class NewspusherJob extends Job
{

    public function getJobName()
    {
        return t("Push scheduled news.");
    }

    public function getJobDescription()
    {
        return t("");
    }

    public function run() 
    {        
        $newspusher = Push::run_job();
              
        return t(
            'The newspusher job submission was successfull.'
        );   	
    }
}    
    
    
    
    




    