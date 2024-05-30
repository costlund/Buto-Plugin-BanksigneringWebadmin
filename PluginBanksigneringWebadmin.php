<?php
class PluginBanksigneringWebadmin{
  public function page_log(){
    wfPlugin::enable('wf/table');
    wfDocument::renderElementFromFolder(__DIR__, __FUNCTION__);
  }
  public function page_log_data(){
    $dir = wfGlobals::getAppDir().'/../buto_data/theme/[theme]/plugin/banksignering/ui/';
    $scandir = wfFilesystem::getScandir($dir);
    $rs = array();
    if(wfUser::hasRole('webadmin')){
      foreach($scandir as $k => $v){
        $log = new PluginWfYml(wfGlobals::getAppDir().'/../buto_data/theme/[theme]/plugin/banksignering/ui/'.$v);
        foreach($log->get() as $k2 => $v2){
          $item = new PluginWfArray($v2);
          $temp = new PluginWfArray();
          $temp->set('date_time', null);
          $temp->set('type', null);
          if($item->get('session/response/auth')){
            $temp->set('type', 'auth');
            $temp->set('date_time', $item->get('session/response/auth_data/date_time'));
          }
          if($item->get('session/response/sign')){
            $temp->set('type', 'sign');
            $temp->set('date_time', $item->get('session/response/sign_data/date_time'));
          }
          $temp->set('status', $item->get('session/response/collectstatus/apiCallResponse/Response/Status'));
          $temp->set('method', $item->get('session/log/method'));
          $temp->set('ip', $item->get('session/log/ip'));
          $rs[] = $temp->get();
        }
      }
    }
    wfPlugin::includeonce('datatable/datatable_1_10_18');
    $datatable = new PluginDatatableDatatable_1_10_18();
    exit($datatable->set_table_data($rs));
  }
}