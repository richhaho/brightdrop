<style type="text/css">
  .count_pending{
    border-radius: 50%;
    border:2px solid red;
    width: 20px;
    height: 20px;
    text-align: center; 
    color: white;
    font-weight: 600;
    margin-right: 25px;
    font-size: 11px;
    background-color: red;
  }
  .active_child a{
    color: white !important;
    
  }
</style>
<!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
      </div>
       
      <ul class="sidebar-menu">
       
       <!--  WORKER SIDE PANEL -->
      @if (Auth::user()->hasRole("Worker"))
          <li class="treeview"><a href=""><h3> WORKER</h3></a> </li>
            <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/logTime')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/worker/needsApproval')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-bar-chart" ></i> <span>Timesheet</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/worker/logTime')) ? 'active_child' : ''}}">
                     <a href="/worker/logTime">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil" ></i> <span>Log Time </span>
                     </a>
                 </li> 
                 <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/needsApproval')) ? 'active_child' : ''}}">
                     <a href="/worker/needsApproval">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa fa-check"></i> <span>Needs Approval  </span>
                         @if (Auth::user()->worker()->timecards()->where('status','pending_worker')->count('id')>0)
                         <span class="pull-right count_pending">{{Auth::user()->worker()->timecards()->where('status','pending_worker')->count('id')}}</span>
                         @endif
                     </a>
                 </li>
                </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/worker/paymentSummaries')) ? 'active_child' : ''}}">
                 <a href="/worker/paymentSummaries">
                     <i class="fa fa fa-folder-open-o"></i> <span>Payment Summaries </span>
                 </a>
            </li>

            <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/summaryPTO')) ? 'active_child' : ''}}">
               <a href="/worker/summaryPTO">
                   <i class="fa fa-stethoscope"></i> <span>Paid Time Off (PTO)  </span>
               </a>
            </li>


            <!-- <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/paidTimeOff')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/worker/pendingPTO')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/worker/summaryPTO')) ? 'active' : ''}}  {{ starts_with( Request::url(), URL::to('/worker/pastPTO')) ? 'active' : ''}}">
             <a href="#">
                 <i class="fa fa-stethoscope" ></i> <span>Paid Time Off (PTO)</span>
                 <span class="pull-right-container">
                   <i class="fa fa-angle-left pull-right"></i>
                 </span>
             </a>
             <ul class="treeview-menu">
               <li class="treeview {{ starts_with( Request::url(), URL::to('/worker/paidTimeOff')) ? 'active_child' : ''}}">
                   <a href="/worker/paidTimeOff">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i><span>Create Request</span>
                   </a>
               </li>
               <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/pendingPTO')) ? 'active_child' : ''}}">
                   <a href="/worker/pendingPTO">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i><span>Pending Approval </span>
                   </a>
               </li>
               <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/summaryPTO')) ? 'active_child' : ''}}">
                   <a href="/worker/summaryPTO">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i><span>Paid Time Off (PTO)  </span>
                   </a>
               </li>
               <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/pastPTO')) ? 'active_child' : ''}}">
                   <a href="/worker/pastPTO">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i><span>Past Requests  </span>
                   </a>
               </li>
              </ul>
            </li> -->
            <li class="treeview {{ starts_with( Request::url(), URL::to('/worker/holidaySchedule')) ? 'active_child' : ''}}">
                 <a href="/worker/holidaySchedule">
                     <i class="fa fa-suitcase"></i> <span>Paid Holidays</span>
                 </a>
            </li>
            


            <!-- <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/reimbursementRequest')) ? 'active' : ''}}  {{ starts_with( Request::url(), URL::to('/worker/pendingReimbursement')) ? 'active' : ''}}
            {{ starts_with( Request::url(), URL::to('/worker/summaryReimbursement')) ? 'active' : ''}}">
             <a href="#">
                 <i class="fa fa-exchange" ></i> <span>Reimbursement Requests</span>
                 <span class="pull-right-container">
                   <i class="fa fa-angle-left pull-right"></i>
                 </span>
             </a>
             <ul class="treeview-menu">
               <li class="treeview {{ starts_with( Request::url(), URL::to('/worker/reimbursementRequest')) ? 'active_child' : ''}}">
                   <a href="/worker/reimbursementRequest">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus" ></i><span>Create Reimbursement Request</span>
                   </a>
               </li>
               <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/pendingReimbursement')) ? 'active_child' : ''}}">
                   <a href="/worker/pendingReimbursement">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i><span>Pending Approval  </span>
                   </a>
               </li>
               <li class="treeview  {{ starts_with( Request::url(), URL::to('/worker/summaryReimbursement')) ? 'active_child' : ''}}">
                   <a href="/worker/summaryReimbursement">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i><span>Reimbursement Summary  </span>
                   </a>
               </li>
              </ul>
            </li> -->
         
           <li class="treeview {{ starts_with( Request::url(), URL::to('/worker/profileWorker')) ? 'active_child' : ''}}">
               <a href="/worker/profileWorker">
                   <i class="fa fa-edit" ></i> <span>Edit Profile Information</span>
               </a>
           </li>
           
           <li class="treeview">
               <a href="/logout">
                   <i class="fa fa-arrow-left" ></i> <span>Logout</span>
               </a>
           </li>
      @endif
           
          
      @if (Auth::user()->hasRole("Account"))
           <!--  ACCOUNT MANAGER - SIDE PANEL -->
            <li class="treeview"><a href=""><h3> ACCOUNT MANAGER</h3></a>  </li>
            <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>
            
            <li class="{{ starts_with( Request::url(), URL::to('/accountManager/currentTimesheets')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/pendingWorkerApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/needsApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/pastTimesheets')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-bar-chart" ></i> <span> Timesheet</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/currentTimesheets')) ? 'active_child' : ''}}">
                     <a href="/accountManager/currentTimesheets">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i> <span>   Current Timesheets</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/pendingWorkerApproval')) ? 'active_child' : ''}}">
                     <a href="/accountManager/pendingWorkerApproval">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i> <span>   Pending Worker Approval</span>
                         <?php
                            $clients = Auth::user()->accountmanager()->clients()->where('status', 'active')->get();
                            $client_ids = $clients->pluck('id')->toArray();
                            $workers = implode(',', $clients->pluck('workers_ids')->toArray());
                            $worker_ids = explode(',',$workers);
                            $count_pending=App\TimeCards::where('status','pending_worker')->whereIn('clients_id', $client_ids)->whereIn('workers_id', $worker_ids)->count('id');
                         ?>
                         @if ($count_pending>0)
                         <span class="pull-right count_pending">{{$count_pending}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/needsApproval')) ? 'active_child' : ''}}">
                     <a href="/accountManager/needsApproval">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> <span> Needs Approval</span>
                          <?php 
                              $count_pending=App\TimeCards::where('status','needs_approval')->whereIn('clients_id', $client_ids)->count('id');
                          ?>
                         @if ($count_pending>0)
                         <span class="pull-right count_pending">{{$count_pending}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/pastTimesheets')) ? 'active_child' : ''}}">
                   <a href="/accountManager/pastTimesheets">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i> <span>Past Timesheets</span>
                   </a>
               </li>
              </ul>
            </li>

            <li class="treeview   {{ starts_with( Request::url(), URL::to('/accountManager/PTOOverride')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/PTONeedsApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/PTOpast')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/PTOsummary')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-stethoscope" ></i> <span>PTO</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 
                 <!-- <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/PTOOverride')) ? 'active_child' : ''}}">
                     <a href="/accountManager/PTOOverride">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i> <span> Create Request</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/PTONeedsApproval')) ? 'active_child' : ''}}">
                     <a href="/accountManager/PTONeedsApproval">
                         &nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i><span>&nbsp; Needs Approval </span>
                          
                     </a>
                 </li> -->
                 <li class="treeview  {{ starts_with( Request::url(), URL::to('/accountManager/PTOsummary')) ? 'active_child' : ''}}">
                   <a href="/accountManager/PTOsummary">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i><span> Paid Time Off (PTO) </span>
                   </a>
                 </li>
                 <!-- <li class="treeview  {{ starts_with( Request::url(), URL::to('/accountManager/PTOpast')) ? 'active_child' : ''}}">
                   <a href="/accountManager/PTOpast">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i><span> Past Requests </span>
                   </a>
                 </li> -->

              </ul>
            </li>
            
            <!-- <li class="treeview  {{ starts_with( Request::url(), URL::to('/accountManager/openCashAdvance')) ? 'active' : ''}}
            {{ starts_with( Request::url(), URL::to('/accountManager/pastCashAdvance')) ? 'active' : ''}}">
             <a href="#">
                 <i class="fa fa-money" ></i> <span>Cash Advance </span>
                 <span class="pull-right-container">
                   <i class="fa fa-angle-left pull-right"></i>
                 </span>
             </a>
             <ul class="treeview-menu">
               <li class="treeview  {{ starts_with( Request::url(), URL::to('/accountManager/openCashAdvance')) ? 'active_child' : ''}}">
                   <a href="/accountManager/openCashAdvance">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i><span> Active Cash Advances </span>
                   </a>
               </li>
               <li class="treeview  {{ starts_with( Request::url(), URL::to('/accountManager/pastCashAdvance')) ? 'active_child' : ''}}">
                   <a href="/accountManager/pastCashAdvance">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i><span> Past Cash Advances </span>
                   </a>
               </li>
              </ul>
            </li> -->

           <li class="treeview  {{ starts_with( Request::url(), URL::to('/accountManager/createAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/accountManager/ReimbursementNeedsApproval')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/accountManager/pendingAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/accountManager/activeAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/accountManager/pastAdjustment')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-exchange" ></i> <span>Adjustment</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createAdjustment')) ? 'active_child' : ''}}">
                     <a href="/accountManager/createAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus" ></i> <span> Create Adjustment</span>
                     </a>
                 </li> 
                 <!-- <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/ReimbursementNeedsApproval')) ? 'active_child' : ''}} ">
                       <a href="/accountManager/ReimbursementNeedsApproval">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-check" ></i> <span> Needs Approval</span>
                       </a>

                  </li> -->
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/pendingAdjustment')) ? 'active_child' : ''}}">
                    <a href="/accountManager/pendingAdjustment">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i> <span>Pending Adjustments  </span>
                    </a>
                  </li>
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/activeAdjustment')) ? 'active_child' : ''}}">
                     <a href="/accountManager/activeAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-refresh" ></i> <span> Active Recurring Adjustments</span>
                     </a>
                 </li> 
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/pastAdjustment')) ? 'active_child' : ''}}">
                     <a href="/accountManager/pastAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span> Past Adjustments</span>
                     </a>
                 </li> 
              </ul>
           </li>

           <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>

           <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createPosition')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/openPositions')) ? 'active' : ''}}  {{ starts_with( Request::url(), URL::to('/accountManager/pastPositions')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/editPosition')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa fa-building" ></i> <span>Candidate Pipeline</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createPosition')) || starts_with( Request::url(), URL::to('/accountManager/editPosition')) ? 'active_child' : ''}}">
                        <?php 
                            $clients = Auth::user()->accountmanager()->clients()->get()->pluck('id')->toArray();
                            $profile=\App\Positions::where('deleted_at',null)->whereIn('clients_id', $clients)->orderBy('updated_at','desc')->first();
                            if($profile) {
                                $id=$profile->id;                       
                        ?>
                        <a href="/accountManager/editPosition/{{$id}}">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Position Builder</span>
                        </a>
                        <?php } else { ?>
                        <a href="/accountManager/createPosition">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Position Builder</span>
                        </a>
                        <?php }?>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/openPositions')) ? 'active_child' : ''}}">
                       <a href="/accountManager/openPositions">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Open Positions</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/pastPositions')) ? 'active_child' : ''}}">
                       <a href="/accountManager/pastPositions">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Past Positions</span>
                       </a>
                   </li>
                </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/hired/needs-finalized')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/hired/pending-review')) ? 'active' : ''}}  {{ starts_with( Request::url(), URL::to('/accountManager/hired/needs-setup')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/hired/completed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa fa-list-alt" ></i> <span>Hired</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/hired/needs-finalized')) ? 'active_child' : ''}}">
                       <a href="/accountManager/hired/needs-finalized">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-book" ></i> <span>Needs Finalized</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/hired/pending-review')) ? 'active_child' : ''}}">
                       <a href="/accountManager/hired/pending-review">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o" ></i> <span>Pending Review</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/hired/needs-setup')) ? 'active_child' : ''}}">
                       <a href="/accountManager/hired/needs-setup">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase" ></i> <span>Needs Setup</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/hired/completed')) ? 'active_child' : ''}}">
                       <a href="/accountManager/hired/completed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Completed</span>
                       </a>
                   </li>
                </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/declined/pending-notice')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/declined/completed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa fa-ban" ></i> <span>Declined</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/declined/pending-notice')) ? 'active_child' : ''}}">
                       <a href="/accountManager/declined/pending-notice">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase" ></i> <span>Pending Notice</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/declined/completed')) ? 'active_child' : ''}}">
                       <a href="/accountManager/declined/completed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Completed</span>
                       </a>
                   </li>
                </ul>
            </li> 

           <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>
           
            <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createClient')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/searchClient')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/profileClient')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-laptop" ></i> <span>Client</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createClient')) ? 'active_child' : ''}}">
                       <a href="/accountManager/createClient">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Create New</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/searchClient')) ? 'active_child' : ''}}">
                       <a href="/accountManager/searchClient">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Search</span>
                       </a>
                   </li>
                  @if(count(Auth::user()->accountmanager()->clients()->get())>0)
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/profileClient')) ? 'active_child' : ''}}">
                     <?php $id=Auth::user()->accountmanager()->clients()->orderBy('updated_at','desc')->where('deleted_at',null)->first()->id;?>
                     <a href="/accountManager/profileClient/{{$id}}">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span>Profile</span>
                     </a>
                  </li>
                  @endif
                    
                </ul>
            </li>


            <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createContact')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/searchContact')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/profileContact')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-envelope" ></i> <span>Contact</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createContact')) ? 'active_child' : ''}}">
                     <a href="/accountManager/createContact">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span> Create New</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/searchContact')) ? 'active_child' : ''}}">
                     <a href="/accountManager/searchContact">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span> Search</span>
                     </a>
                 </li>
                 @if(count(Auth::user()->accountmanager()->contacts()->get())>0)
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/profileContact')) ? 'active_child' : ''}}">
                    <?php $id=Auth::user()->accountmanager()->contacts()->orderBy('updated_at','desc')->where('deleted_at',null)->first()->id;?>
                     <a href="/accountManager/profileContact/{{$id}}">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span> Profile</span>
                     </a>
                 </li>
                 @endif
                  
              </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createWorker')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/searchWorker')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/searchWorkerHired')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/accountManager/profileWorker')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-gavel" ></i> <span>Worker</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/createWorker')) ? 'active_child' : ''}}">
                     <a href="/accountManager/createWorker">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Create New</span>
                     </a>
                 </li>
                 <li class="treeview {{  Request::url() == URL::to('/accountManager/searchWorker') ? 'active_child' : ''}}">
                     <a href="/accountManager/searchWorker">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Search - Candidates</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/searchWorkerHired')) ? 'active_child' : ''}}">
                     <a href="/accountManager/searchWorkerHired">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Search - Hired Workers</span>
                     </a>
                 </li>
                 @if(count(Auth::user()->accountmanager()->workers()->get())>0)
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/accountManager/profileWorker')) ? 'active_child' : ''}}">
                     <?php $id=Auth::user()->accountmanager()->workers()->orderBy('updated_at','desc')->where('deleted_at',null)->first()->id;?> 
                     <a href="/accountManager/profileWorker/{{$id}}"> 
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span> Profile</span>
                     </a>
                  </li> 
                  @endif
                  
              </ul>
            </li>

            <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>
           
           <li class="treeview">
               <a href="/logout">
                   <i class="fa fa-arrow-left" ></i> <span>Logout</span>
               </a>
           </li>
      @endif

      @if (Auth::user()->hasRole("Payroll"))

           <!--  PAYROLL MANAGER - SIDE PANEL -->
           <li class="treeview"><a href=""><h3> PAYROLL MANAGER</h3></a> </li>
            <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/immediatePayWU')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/immediatePayVeem')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/biWeeklyWU')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/biWeeklyVeem')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/viewClosed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-usd" ></i> <span>Payments</span>
                   @if (\App\Payments::where('status','!=','Paid')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','!=','Paid')->count('id')}}</span>
                         @endif
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">     
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/immediatePayWU')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/immediatePayWU">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calculator" ></i> <span>Immediate Pay (WU)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','immediate')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','immediate')->count('id')}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/immediatePayVeem')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/immediatePayVeem">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-building" ></i> <span>Immediate Pay (Veem)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','immediate')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','immediate')->count('id')}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/biWeeklyWU')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/biWeeklyWU">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar" ></i> <span>Bi-Weekly (WU)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','bi-weekly')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','bi-weekly')->count('id')}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/biWeeklyVeem')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/biWeeklyVeem">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar" ></i> <span>Bi-Weekly (Veem)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','bi-weekly')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','bi-weekly')->count('id')}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/viewClosed')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/viewClosed">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Past Payments</span>
                          
                     </a>
                 </li>
              </ul>
            </li>
            <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesPendingClientApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesAutomatic')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesManual')) ? 'active' : ''}} {{ starts_with( Request::url(), 
            URL::to('/payrollManager/invoicesNeedsSent')) ? 'active' : ''}} {{ starts_with( Request::url(), 
            URL::to('/payrollManager/invoicesNeedsProcessed')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesBankVerifications')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesViewClosed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-suitcase" ></i> <span>Invoices</span>
                   @if (\App\Invoices::where('deleted_at',null)->where('status','!=','Bank Verified')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','!=','Bank Verified')->count('id')}}</span>
                           @endif 
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesManual')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/invoicesManual">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-book" ></i> <span>Needs Finalized (Manual)</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('invoice_method','manual')->where('status','Needs Sent')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('invoice_method','manual')->where('status','Needs Sent')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesAutomatic')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/invoicesAutomatic">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-bolt" ></i> <span>Needs Finalized (Sys Gen)</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('invoice_method','automatically')->where('status','Needs Sent')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('invoice_method','automatically')->where('status','Needs Sent')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesNeedsSent')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/invoicesNeedsSent">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-send" ></i> <span>Needs Sent</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Needs Sent From Finalized')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Needs Sent From Finalized')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesPendingClientApproval')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/invoicesPendingClientApproval">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o" ></i> <span>Pending Client Approval</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Pending Client Approval')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Pending Client Approval')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesNeedsProcessed')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/invoicesNeedsProcessed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase" ></i> <span>Needs Processed</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Needs Processed')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Needs Processed')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesBankVerifications')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/invoicesBankVerifications">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-credit-card" ></i> <span>Bank Verifications</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Needs Bank Verification')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Needs Bank Verification')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/invoicesViewClosed')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/invoicesViewClosed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Past Invoices</span>
                           
                       </a>
                   </li>
              </ul>
           </li>
           <li class="treeview  {{ starts_with( Request::url(), URL::to('/payrollManager/createAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/payrollManager/ReimbursementNeedsApproval')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/payrollManager/needApprovalAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/payrollManager/pendingAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/payrollManager/activeAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/payrollManager/pastAdjustment')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-exchange" ></i> <span>Adjustment</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/createAdjustment')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/createAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus" ></i> <span> Create Adjustment</span>
                     </a>
                 </li> 
                 
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/needApprovalAdjustment')) ? 'active_child' : ''}}">
                    <a href="/payrollManager/needApprovalAdjustment">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> <span>Needs Approval </span>
                       @if (\App\OneTimeAdjustments::where('status','Pending')->count('id')+\App\RecurringAdjustments::where('status','Pending')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\OneTimeAdjustments::where('status','Pending')->count('id')+\App\RecurringAdjustments::where('status','Pending')->count('id')}}</span>
                           @endif
                    </a>
                  </li>
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/pendingAdjustment')) ? 'active_child' : ''}}">
                    <a href="/payrollManager/pendingAdjustment">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i> <span>Pending Adjustments  </span>
                    </a>
                  </li>
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/activeAdjustment')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/activeAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-refresh" ></i> <span> Active Recurring Adjustments</span>
                     </a>
                 </li> 
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/pastAdjustment')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/pastAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span> Past Adjustments</span>
                     </a>
                 </li> 
              </ul>
           </li>


           <li class="{{ starts_with( Request::url(), URL::to('/payrollManager/currentTimesheets')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/pendingWorkerApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/needsApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/pastTimesheets')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-bar-chart" ></i> <span> Timesheet</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/currentTimesheets')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/currentTimesheets">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i> <span>   Current Timesheets</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/pendingWorkerApproval')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/pendingWorkerApproval">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i> <span>   Pending Worker Approval</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/needsApproval')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/needsApproval">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> <span> Needs Approval</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/pastTimesheets')) ? 'active_child' : ''}}">
                   <a href="/payrollManager/pastTimesheets">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i> <span>Past Timesheets</span>
                   </a>
               </li>
              </ul>
            </li>

            <li class="treeview   {{ starts_with( Request::url(), URL::to('/payrollManager/PTOOverride')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/PTONeedsApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/PTOpast')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/PTOsummary')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-stethoscope" ></i> <span>PTO</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 
                 <!-- <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/PTOOverride')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/PTOOverride">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i> <span> Create Request</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/PTONeedsApproval')) ? 'active_child' : ''}}">
                     <a href="/payrollManager/PTONeedsApproval">
                         &nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i><span>&nbsp; Needs Approval </span>
                          
                     </a>
                 </li> -->
                 <li class="treeview  {{ starts_with( Request::url(), URL::to('/payrollManager/PTOsummary')) ? 'active_child' : ''}}">
                   <a href="/payrollManager/PTOsummary">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i><span> Paid Time Off (PTO) </span>
                   </a>
                 </li>
                 <!-- <li class="treeview  {{ starts_with( Request::url(), URL::to('/payrollManager/PTOpast')) ? 'active_child' : ''}}">
                   <a href="/payrollManager/PTOpast">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i><span> Past Requests </span>
                   </a>
               </li> -->

              </ul>
            </li>

           
           
           <!-- <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/createCashAdvance')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/openCashAdvance')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/payrollManager/pastCashAdvance')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-database" ></i> <span>Cash Advance</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/createCashAdvance')) ? 'active_child' : ''}}">
                       <a href="/payrollManager/createCashAdvance">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus" ></i> <span> Create Cash Advance </span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/openCashAdvance')) ? 'active_child' : ''}} ">
                       <a href="/payrollManager/openCashAdvance">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-edit" ></i> <span> Active Cash Advances</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/payrollManager/pastCashAdvance')) ? 'active_child' : ''}} ">
                       <a href="/payrollManager/pastCashAdvance">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span> Past Cash Advances</span>
                       </a>
                   </li>
                   
                </ul>
            </li> -->

           


           <li class="treeview">
               <a href="/logout">
                   <i class="fa fa-arrow-left" ></i> <span>Logout</span>
               </a>
           </li>  

      @endif

      @if (Auth::user()->hasRole("Contact"))
           <!--  CONTACT MANAGER - SIDE PANEL -->
           <li class="treeview"><a href=""><h3> CONTACT</h3></a> </li>
           <!--<li class="treeview {{ starts_with( Request::url(), URL::to('/contact/needsApproval')) ? 'active' : ''}}">-->
           <!--    <a href="/contact/needsApproval">-->
           <!--        <i class="fa fa-book" ></i> <span>Needs Approval</span>-->
           <!--    </a>-->
           <!--</li>-->
           <li class="treeview {{ starts_with( Request::url(), URL::to('/contact/openPositions')) ? 'active' : ''}}">
                <a href="/contact/openPositions">
                    <i class="fa fa-building" ></i> <span>Open Positions</span>
                </a>
           </li>
           <li class="treeview {{ starts_with( Request::url(), URL::to('/contact/pastInvoices')) ? 'active' : ''}}">
               <a href="/contact/pastInvoices">
                   <i class="fa fa-folder-open-o" ></i> <span>Past Invoices</span>
               </a>
           </li>
           <li class="treeview {{ starts_with( Request::url(), URL::to('/contact/holidaySchedule')) ? 'active' : ''}}">
               <a href="/contact/holidaySchedule">
                   <i class="fa fa-suitcase" ></i> <span> Holiday Schedule </span>
               </a>
           </li>
           <li class="treeview {{ starts_with( Request::url(), URL::to('/contact/PTOInformation')) ? 'active' : ''}}">
               <a href="/contact/PTOInformation">
                   <i class="fa fa-stethoscope" ></i> <span>PTO Information</span>
               </a>
           </li>

           <li class="treeview">
               <a href="/logout">
                   <i class="fa fa-arrow-left" ></i> <span>Logout</span>
               </a>
           </li> 

      @endif

      @if (Auth::user()->hasRole("Admin"))
           <!--  ADMIN - SIDE PANEL -->
           <li class="treeview"><a href=""><h3> ADMIN</h3></a> </li>
            <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>
            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/immediatePayWU')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/immediatePayVeem')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/biWeeklyWU')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/biWeeklyVeem')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/viewClosed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-usd" ></i> <span>Payments</span>
                   @if (\App\Payments::where('status','!=','Paid')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','!=','Paid')->count('id')}}</span>
                         @endif
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">     
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/immediatePayWU')) ? 'active_child' : ''}}">
                     <a href="/admin/immediatePayWU">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calculator" ></i> <span>Immediate Pay (WU)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','immediate')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','immediate')->count('id')}}</span>
                         @endif
                          
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/immediatePayVeem')) ? 'active_child' : ''}}">
                     <a href="/admin/immediatePayVeem">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-building" ></i> <span>Immediate Pay (Veem)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','immediate')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','immediate')->count('id')}}</span>
                         @endif
                          
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/biWeeklyWU')) ? 'active_child' : ''}}">
                     <a href="/admin/biWeeklyWU">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar" ></i> <span>Bi-Weekly (WU)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','bi-weekly')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Western Union')->where('payment_type','bi-weekly')->count('id')}}</span>
                         @endif 
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/biWeeklyVeem')) ? 'active_child' : ''}}">
                     <a href="/admin/biWeeklyVeem">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar" ></i> <span>Bi-Weekly (Veem)</span>
                         @if (\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','bi-weekly')->count('id')>0)
                         <span class="pull-right count_pending">{{\App\Payments::where('status','Pending')->where('payment_method','Veem')->where('payment_type','bi-weekly')->count('id')}}</span>
                         @endif 
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/viewClosed')) ? 'active_child' : ''}}">
                     <a href="/admin/viewClosed">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Past Payments</span>
                          
                     </a>
                 </li>
              </ul>
            </li>
            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesPendingClientApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/invoicesAutomatic')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/invoicesManual')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/invoicesNeedsSent')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/invoicesNeedsProcessed')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/invoicesBankVerifications')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/invoicesViewClosed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-suitcase" ></i> <span>Invoices</span>
                   @if (\App\Invoices::where('deleted_at',null)->where('status','!=','Bank Verified')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','!=','Bank Verified')->count('id')}}</span>
                           @endif 
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesManual')) ? 'active_child' : ''}}">
                       <a href="/admin/invoicesManual">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-book" ></i> <span>Needs Finalized (Manual)</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('invoice_method','manual')->where('status','Needs Sent')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('invoice_method','manual')->where('status','Needs Sent')->count('id')}}</span>
                           @endif 
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesAutomatic')) ? 'active_child' : ''}}">
                       <a href="/admin/invoicesAutomatic">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-bolt" ></i> <span>Needs Finalized (Sys Gen)</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('invoice_method','automatically')->where('status','Needs Sent')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('invoice_method','automatically')->where('status','Needs Sent')->count('id')}}</span>
                           @endif 
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesNeedsSent')) ? 'active_child' : ''}}">
                       <a href="/admin/invoicesNeedsSent">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-send" ></i> <span>Needs Sent</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Needs Sent From Finalized')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Needs Sent From Finalized')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesPendingClientApproval')) ? 'active_child' : ''}}">
                       <a href="/admin/invoicesPendingClientApproval">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o" ></i> <span>Pending Client Approval</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Pending Client Approval')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Pending Client Approval')->count('id')}}</span>
                           @endif 
                            
                       </a>
                   </li>
                   
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesNeedsProcessed')) ? 'active_child' : ''}}">
                       <a href="/admin/invoicesNeedsProcessed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase" ></i> <span>Needs Processed</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Needs Processed')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Needs Processed')->count('id')}}</span>
                           @endif 
                            
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesBankVerifications')) ? 'active_child' : ''}}">
                       <a href="/admin/invoicesBankVerifications">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-credit-card" ></i> <span>Bank Verifications</span>
                           @if (\App\Invoices::where('deleted_at',null)->where('status','Needs Bank Verification')->count('id')>0)
                           <span class="pull-right count_pending">{{\App\Invoices::where('deleted_at',null)->where('status','Needs Bank Verification')->count('id')}}</span>
                           @endif
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/invoicesViewClosed')) ? 'active_child' : ''}}">
                       <a href="/admin/invoicesViewClosed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Past Invoices</span>
                           
                       </a>
                   </li>
              </ul>
           </li>
           <li class="treeview  {{ starts_with( Request::url(), URL::to('/admin/createAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/admin/ReimbursementNeedsApproval')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/admin/needApprovalAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/admin/pendingAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/admin/activeAdjustment')) ? 'active' : ''}}
           {{ starts_with( Request::url(), URL::to('/admin/pastAdjustment')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-exchange" ></i> <span>Adjustment</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createAdjustment')) ? 'active_child' : ''}}">
                     <a href="/admin/createAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus" ></i> <span> Create Adjustment</span>
                     </a>
                 </li> 
                 
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/needApprovalAdjustment')) ? 'active_child' : ''}}">
                    <a href="/admin/needApprovalAdjustment">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> <span>Needs Approval </span>
                    </a>
                  </li>
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/pendingAdjustment')) ? 'active_child' : ''}}">
                    <a href="/admin/pendingAdjustment">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i> <span>Pending Adjustments  </span>
                    </a>
                  </li>
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/activeAdjustment')) ? 'active_child' : ''}}">
                     <a href="/admin/activeAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-refresh" ></i> <span> Active Recurring Adjustments</span>
                     </a>
                 </li> 
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/pastAdjustment')) ? 'active_child' : ''}}">
                     <a href="/admin/pastAdjustment">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span> Past Adjustments</span>
                     </a>
                 </li> 
              </ul>
           </li>


           <li class="{{ starts_with( Request::url(), URL::to('/admin/currentTimesheets')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/pendingWorkerApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/needsApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/pastTimesheets')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-bar-chart" ></i> <span> Timesheet</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">

                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/currentTimesheets')) ? 'active_child' : ''}}">
                     <a href="/admin/currentTimesheets">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-pencil"></i> <span>   Current Timesheets</span>
                     </a>
                 </li>
                 
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/pendingWorkerApproval')) ? 'active_child' : ''}}">
                     <a href="/admin/pendingWorkerApproval">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o"></i> <span>   Pending Worker Approval</span>
                         <?php
                            $clients = App\Clients::where('deleted_at',null)->where('status', 'active')->get();
                            $client_ids = $clients->pluck('id')->toArray();
                            $workers = implode(',', $clients->pluck('workers_ids')->toArray());
                            $worker_ids = explode(',',$workers);
                            $count_pending=App\TimeCards::where('status','pending_worker')->whereIn('clients_id', $client_ids)->whereIn('workers_id', $worker_ids)->count('id');
                          ?>
                         @if ($count_pending>0)
                         <span class="pull-right count_pending">{{$count_pending}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/needsApproval')) ? 'active_child' : ''}}">
                     <a href="/admin/needsApproval">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i> <span> Needs Approval</span>
                         <?php 
                            $count_pending=App\TimeCards::where('status','needs_approval')->whereIn('clients_id', $client_ids)->count('id');
                          ?>
                         @if ($count_pending>0)
                         <span class="pull-right count_pending">{{$count_pending}}</span>
                         @endif
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/pastTimesheets')) ? 'active_child' : ''}}">
                   <a href="/admin/pastTimesheets">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i> <span>Past Timesheets</span>
                   </a>
               </li>
              </ul>
            </li>

            <li class="treeview   {{ starts_with( Request::url(), URL::to('/admin/PTOOverride')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/PTONeedsApproval')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/PTOpast')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/PTOsummary')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-stethoscope" ></i> <span>PTO</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 
                 <!-- <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/PTOOverride')) ? 'active_child' : ''}}">
                     <a href="/admin/PTOOverride">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus"></i> <span> Create Request</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/PTONeedsApproval')) ? 'active_child' : ''}}">
                     <a href="/admin/PTONeedsApproval">
                         &nbsp;&nbsp;&nbsp;<i class="fa fa-check"></i><span>&nbsp; Needs Approval </span>
                          
                     </a>
                 </li> -->
                 <li class="treeview  {{ starts_with( Request::url(), URL::to('/admin/PTOsummary')) ? 'active_child' : ''}}">
                   <a href="/admin/PTOsummary">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-list"></i><span> Paid Time Off (PTO) </span>
                   </a>
                 </li>
                 <!-- <li class="treeview  {{ starts_with( Request::url(), URL::to('/admin/PTOpast')) ? 'active_child' : ''}}">
                   <a href="/admin/PTOpast">
                       &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o"></i><span> Past Requests </span>
                   </a>
               </li> -->

              </ul>
            </li>

           
           
           <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createCashAdvance')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/openCashAdvance')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/pastCashAdvance')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-database" ></i> <span>Cash Advance</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createCashAdvance')) ? 'active_child' : ''}}">
                       <a href="/admin/createCashAdvance">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-plus" ></i> <span> Create Cash Advance </span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/openCashAdvance')) ? 'active_child' : ''}} ">
                       <a href="/admin/openCashAdvance">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-edit" ></i> <span> Active Cash Advances</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/pastCashAdvance')) ? 'active_child' : ''}} ">
                       <a href="/admin/pastCashAdvance">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span> Past Cash Advances</span>
                       </a>
                   </li>
                   
                </ul>
            </li>


            <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createPosition')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/openPositions')) ? 'active' : ''}}  {{ starts_with( Request::url(), URL::to('/admin/pastPositions')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/editPosition')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa fa-building" ></i> <span>Candidate Pipeline</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createPosition')) || starts_with( Request::url(), URL::to('/admin/editPosition')) ? 'active_child' : ''}}">
                        <?php $profile=\App\Positions::where('deleted_at',null)->orderBy('updated_at','desc')->first();
                            if($profile) {
                                $id=$profile->id;                       
                        ?>
                        <a href="/admin/editPosition/{{$id}}">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Position Builder</span>
                        </a>
                        <?php } else { ?>
                        <a href="/admin/createPosition">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Position Builder</span>
                        </a>
                        <?php }?>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/openPositions')) ? 'active_child' : ''}}">
                       <a href="/admin/openPositions">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Open Positions</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/pastPositions')) ? 'active_child' : ''}}">
                       <a href="/admin/pastPositions">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Past Positions</span>
                       </a>
                   </li>
                </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/hired/needs-finalized')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/hired/pending-review')) ? 'active' : ''}}  {{ starts_with( Request::url(), URL::to('/admin/hired/needs-setup')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/hired/completed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa fa-list-alt" ></i> <span>Hired</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/hired/needs-finalized')) ? 'active_child' : ''}}">
                       <a href="/admin/hired/needs-finalized">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-book" ></i> <span>Needs Finalized</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/hired/pending-review')) ? 'active_child' : ''}}">
                       <a href="/admin/hired/pending-review">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o" ></i> <span>Pending Review</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/hired/needs-setup')) ? 'active_child' : ''}}">
                       <a href="/admin/hired/needs-setup">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase" ></i> <span>Needs Setup</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/hired/completed')) ? 'active_child' : ''}}">
                       <a href="/admin/hired/completed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Completed</span>
                       </a>
                   </li>
                </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/declined/pending-notice')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/declined/completed')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa fa-ban" ></i> <span>Declined</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/declined/pending-notice')) ? 'active_child' : ''}}">
                       <a href="/admin/declined/pending-notice">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase" ></i> <span>Pending Notice</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/declined/completed')) ? 'active_child' : ''}}">
                       <a href="/admin/declined/completed">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open-o" ></i> <span>Completed</span>
                       </a>
                   </li>
                </ul>
            </li>


            <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createAccountManager')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/searchAccountManager')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/profileAccountManager')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-male" ></i> <span>Account Manager</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">     
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createAccountManager')) ? 'active_child' : ''}}">
                     <a href="/admin/createAccountManager">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span> Create New</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/searchAccountManager')) ? 'active_child' : ''}}">
                     <a href="/admin/searchAccountManager">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span> Search</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/profileAccountManager')) ? 'active_child' : ''}}">
                    <?php $profile=\App\AccountManagers::where('deleted_at',null)->orderBy('updated_at','desc')->first();
                        if($profile) {
                          $id=$profile->id;                       
                    ?>
                      <a href="/admin/profileAccountManager/{{$id}}"> 
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span> Profile</span>
                      </a>
                    <?php } ?> 
                 </li> 
              </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createAdmin')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/searchAdmin')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/profileAdmin')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-users" ></i> <span>Admin</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createAdmin')) ? 'active_child' : ''}}">
                       <a href="/admin/createAdmin">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span> Create New</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/searchAdmin')) ? 'active_child' : ''}}">
                       <a href="/admin/searchAdmin">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span> Search</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/profileAdmin')) ? 'active_child' : ''}}">
                        
                       <?php $profile=\App\Admins::where('deleted_at',null)->orderBy('updated_at','desc')->first();
                        if($profile) {
                          $id=$profile->id;                       
                       ?>
                       <a href="/admin/profileAdmin/{{$id}}"> 
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span> Profile</span>
                       </a>
                       <?php } ?>
                   </li> 
                </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createClient')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/searchClient')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/profileClient')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-laptop" ></i> <span>Client</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createClient')) ? 'active_child' : ''}}">
                       <a href="/admin/createClient">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Create New</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/searchClient')) ? 'active_child' : ''}}">
                       <a href="/admin/searchClient">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Search</span>
                       </a>
                   </li>
                  
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/profileClient')) ? 'active_child' : ''}}">
                     <?php $profile=\App\Clients::where('deleted_at',null)->orderBy('updated_at','desc')->first();
                        if($profile) {
                          $id=$profile->id;                       
                     ?>
                     <a href="/admin/profileClient/{{$id}}">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span>Profile</span>
                     </a>
                     <?php } ?>
                  </li>
                    
                </ul>
            </li>


            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createContact')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/searchContact')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/profileContact')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-envelope" ></i> <span>Contact</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createContact')) ? 'active_child' : ''}}">
                     <a href="/admin/createContact">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span> Create New</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/searchContact')) ? 'active_child' : ''}}">
                     <a href="/admin/searchContact">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span> Search</span>
                     </a>
                 </li>
                 
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/profileContact')) ? 'active_child' : ''}}">
                    
                    <?php $profile=\App\Contacts::where('deleted_at',null)->orderBy('updated_at','desc')->first();
                        if($profile) {
                          $id=$profile->id;                       
                    ?>
                     <a href="/admin/profileContact/{{$id}}">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span> Profile</span>
                     </a>
                    <?php } ?>
                 </li>
                  
              </ul>
            </li>


            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createPayrollManager')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/searchPayrollManager')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/profilePayrollManager')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-bank" ></i> <span>Payroll Manager</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu"> 
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createPayrollManager')) ? 'active_child' : ''}}">
                       <a href="/admin/createPayrollManager">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span> Create New</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/searchPayrollManager')) ? 'active_child' : ''}}">
                       <a href="/admin/searchPayrollManager">
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span> Search</span>
                       </a>
                   </li>
                   <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/profilePayrollManager')) ? 'active_child' : ''}}">
                       <?php $profile=\App\PayrollManagers::where('deleted_at',null)->orderBy('updated_at','desc')->first();
                        if($profile) {
                          $id=$profile->id;                       
                       ?>
                       <a href="/admin/profilePayrollManager/{{$id}}"> 
                           &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span> Profile</span>
                       </a>
                       <?php }?>
                   </li>
                </ul>
            </li>

            <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createWorker')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/searchWorker')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/searchWorkerHired')) ? 'active' : ''}} {{ starts_with( Request::url(), URL::to('/admin/profileWorker')) ? 'active' : ''}}">
               <a href="#">
                   <i class="fa fa-gavel" ></i> <span>Worker</span>
                   <span class="pull-right-container">
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
               </a>
               <ul class="treeview-menu">
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/createWorker')) ? 'active_child' : ''}}">
                     <a href="/admin/createWorker">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user-plus" ></i> <span>Create New</span>
                     </a>
                 </li>
                 <li class="treeview {{ ( Request::url() == URL::to('/admin/searchWorker')) ? 'active_child' : ''}}">
                     <a href="/admin/searchWorker">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Search - Candidates</span>
                     </a>
                 </li>
                 <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/searchWorkerHired')) ? 'active_child' : ''}}">
                     <a href="/admin/searchWorkerHired">
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" ></i> <span>Search - Hired Workers</span>
                     </a>
                 </li>
                  <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/profileWorker')) ? 'active_child' : ''}}">
                     <?php $profile=\App\Workers::where('deleted_at',null)->orderBy('updated_at','desc')->first();
                        if($profile) {
                          $id=$profile->id;                       
                     ?>
                     <a href="/admin/profileWorker/{{$id}}"> 
                         &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user" ></i> <span> Profile</span>
                     </a>
                   <?php } ?>
                  </li> 
                  
              </ul>
            </li>

            

            <li class="treeview" style="border-bottom: 3px solid #408080;margin:5px"></li>



           <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/holidaySchedule')) ? 'active_child' : ''}}">
               <a href="/admin/holidaySchedule">
                   <i class="fa fa-suitcase" ></i> <span> Holiday Schedule - Default</span>
               </a>
           </li> 
           <li class="treeview {{ starts_with( Request::url(), URL::to('/admin/globalFileds')) ? 'active_child' : ''}}">
               <a href="/admin/globalFileds">
                   <i class="fa fa-globe" ></i> <span> Global Fields</span>
               </a>
           </li>  
           
           
           <li class="treeview">
               <a href="/logout">
                   <i class="fa fa-arrow-left" ></i> <span>Logout</span>
               </a>
           </li> 
      @endif    
       </ul>
        
    </section>
    <!-- /.sidebar -->
  </aside>
