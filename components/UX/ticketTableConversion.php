<?php
// UNIVERSAL CONVERSION FUNCTION
function convertPlainDataToTableRow($n)
{
    $c_subTypes = ["-","Worker Registration","Worker Certification","User Home Verification","Billing Dispute","Rating Dispute","Job Post Issue","Job Order Issue","Complaint on other Users behavior","General Complaint","Inquiry","App Guidance","App Issues","Account Issue","Password Issue","Messaging Issue","Database Issue"];
    $c_issue_id = property_exists($n, 'issue_id') ? ($n->issue_id > 0 || is_numeric($n->issue_id) ? $n->issue_id :"-" ) : "-";                
    $c_head = "GEN";
    if($c_issue_id>=1&&$c_issue_id<=3){
        $c_head = "REG";
    }
    if($c_issue_id>=4&&$c_issue_id<=9){
        $c_head = "DIS";
    }
    if($c_issue_id>=12&&$c_issue_id<=16){
        $c_head = "TEC";
    }       
    $c_statusTypes=["-","New","Ongoing","Resolved","Closed"];             
    $c_ticket_id = property_exists($n, 'id') ? $n->id : "-";
    $c_ticket_no = $c_head."-".str_pad($c_ticket_id,  5, "0",STR_PAD_LEFT);
    $c_type = $c_subTypes[$c_issue_id ];
    $c_status = property_exists($n, 'status') ? $c_statusTypes[$n->status] : "-";
    $c_lname =property_exists($n, 'last_name') ? $n->last_name != null ? $n->last_name[0]."." : "-" : "";
    $c_agent =property_exists($n, 'first_name') ? $n->first_name != null ? $n->first_name." ".$c_lname: "none" : "-";

    $c_dateString_updated = property_exists($n, 'last_updated_on') ? $n->last_updated_on : null;
    $c_dateString_assigned = property_exists($n, 'assigned_on') ? $n->assigned_on : null;
    $c_dateString_created  = property_exists($n, 'created_on') ? $n->created_on : null;

    $c_dateobj_updated =  $c_dateString_updated != null ? new DateTime($c_dateString_updated): null;
    $c_dateobj_assigned = $c_dateString_assigned != null ? new DateTime($c_dateString_assigned): null;
    $c_dateobj_created =  $c_dateString_created  != null ? new DateTime($c_dateString_created): null;
    
    $c_last_updated =  $c_dateobj_updated != null ? date_format($c_dateobj_updated, 'M d, Y g:i A') : "-";
    $c_date_assigned =  $c_dateobj_assigned != null ? date_format($c_dateobj_assigned, 'M d, Y g:i A') : "-";
    $c_date_created =  $c_dateobj_created != null ? date_format($c_dateobj_created, 'M d, Y g:i A') : "-";
    
    return [$c_ticket_no, $c_type, $c_status, $c_agent, $c_last_updated, $c_date_assigned, $c_date_created, $c_ticket_id];
}


function convertNotification_PlainDataToTableRow_accept_read_decline_delete($n, $read=null)
{
    $c_read_btn = $read==null?"<i class='far fa-eye-slash'></i>":"<i class='far fa-eye'></i>";
    $c_notif_arr = array("Follow Up","Transfer Req.","Escalation Req.","Access Req.","Override Req.","Override Notice");
    $c_sender = property_exists($n, 'sender') ? $n->sender : "-";
    $c_empID = property_exists($n, 'generated_by') && is_numeric($n->generated_by) ? $n->generated_by : "-";                   
    $c_notif_type_ID = property_exists($n, 'notification_type_id') && is_numeric($n->notification_type_id) ? $n->notification_type_id : "-";
    
    $c_notif_type = $c_notif_type_ID >count( $c_notif_arr) || $c_notif_type_ID < 0 ? "-" :  $c_notif_arr[$c_notif_type_ID-1];
    $c_notes = property_exists($n, 'system_generated_description') ? $n->system_generated_description : "-";

    $c_date_time = property_exists($n, 'created_on') ? $n->created_on : "-";
    $c_date = "-";
    $c_time = "-";
    if( $c_date_time != "-"){
        $date = new DateTime($c_date_time);
        $c_date = $date->format('M j, Y');
        $c_time = $date->format('g:i A');
    }

    $c_ticket_ID = property_exists($n, 'support_ticket_id') ? $n->support_ticket_id : "-";
    $c_notif_ID = property_exists($n, 'id') ? $n->id : "-";

    return [$c_empID,$c_sender, $c_notif_type,
    "<i class='fas fa-check'></i>",$c_read_btn,"<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  
    $c_notes, $c_date ,$c_time,$c_ticket_ID,$c_notif_ID];
}

function convertNotification_PlainDataToTableRow_accept_read_decline_delete______has_read($n){
    // $c_read_btn = $read==null?"<i class='far fa-eye-slash'></i>":"<i class='far fa-eye'></i>";
    $c_notif_arr = array("Follow Up","Transfer Req.","Escalation Req.","Access Req.","Override Req.","Override Notice");
    $c_sender = property_exists($n, 'sender') ? $n->sender : "-";
    $c_empID = property_exists($n, 'generated_by') && is_numeric($n->generated_by) ? $n->generated_by : "-";                   
    $c_notif_type_ID = property_exists($n, 'notification_type_id') && is_numeric($n->notification_type_id) ? $n->notification_type_id : "-";
    
    $c_notif_type = $c_notif_type_ID >count( $c_notif_arr) || $c_notif_type_ID < 0 ? "-" :  $c_notif_arr[$c_notif_type_ID-1];
    $c_notes = property_exists($n, 'system_generated_description') ? $n->system_generated_description : "-";

    $c_date_time = property_exists($n, 'created_on') ? $n->created_on : "-";
    $c_date = "-";
    $c_time = "-";
    if( $c_date_time != "-"){
        $date = new DateTime($c_date_time);
        $c_date = $date->format('M j, Y');
        $c_time = $date->format('g:i A');
    }

    $c_ticket_ID = property_exists($n, 'support_ticket_id') ? $n->support_ticket_id : "-";
    $c_notif_ID = property_exists($n, 'id') ? $n->id : "-";

    return [$c_empID,$c_sender, $c_notif_type,
    "<i class='fas fa-check'></i>","<i class='far fa-eye'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",  
    $c_notes, $c_date ,$c_time,$c_ticket_ID,$c_notif_ID];
}


function convertNotification_PlainDataToTableRow_accept_decline_delete($n)
{    
    $c_notif_arr = array("Follow Up","Transfer Req.","Escalation Req.","Access Req.","Override Req.","Override Notice");
    $c_sender = property_exists($n, 'sender') ? $n->sender : "-";
    $c_empID = property_exists($n, 'generated_by') && is_numeric($n->generated_by) ? $n->generated_by : "-";                   
    $c_notif_type_ID = property_exists($n, 'notification_type_id') && is_numeric($n->notification_type_id) ? $n->notification_type_id : "-";
    
    $c_notif_type = $c_notif_type_ID >count( $c_notif_arr) || $c_notif_type_ID < 0 ? "-" :  $c_notif_arr[$c_notif_type_ID-1];
    $c_notes = property_exists($n, 'system_generated_description') ? $n->system_generated_description : "-";

    $c_date_time = property_exists($n, 'created_on') ? $n->created_on : "-";
    $c_date = "-";
    $c_time = "-";
    if( $c_date_time != "-"){
        $date = new DateTime($c_date_time);
        $c_date = $date->format('M j, Y');
        $c_time = $date->format('g:i A');
    }

    $c_ticket_ID = property_exists($n, 'support_ticket_id') ? $n->support_ticket_id : "-";
    $c_notif_ID = property_exists($n, 'id') ? $n->id : "-";

    return[$c_empID, $c_sender,$c_notif_type,
    "<i class='fas fa-check'></i>","<i class='fas fa-times'></i>","<i class='fas fa-trash-alt'></i>",
    $c_notes ,$c_date ,$c_time,$c_ticket_ID,$c_notif_ID
    ];
}


function convertNotification_PlainDataToTableRow_delete($n)
{   
    $c_notif_arr = array("Follow Up","Transfer Req.","Escalation Req.","Access Req.","Override Req.","Override Notice");
    $c_sender = property_exists($n, 'sender') ? $n->sender : "-";
    $c_empID = property_exists($n, 'generated_by') && is_numeric($n->generated_by) ? $n->generated_by : "-";                   
    $c_notif_type_ID = property_exists($n, 'notification_type_id') && is_numeric($n->notification_type_id) ? $n->notification_type_id : "-";
    
    $c_notif_type = $c_notif_type_ID >count( $c_notif_arr) || $c_notif_type_ID < 0 ? "-" :  $c_notif_arr[$c_notif_type_ID-1];
    $c_notes = property_exists($n, 'system_generated_description') ? $n->system_generated_description : "-";

    $c_date_time = property_exists($n, 'created_on') ? $n->created_on : "-";
    $c_date = "-";
    $c_time = "-";
    if( $c_date_time != "-"){
        $date = new DateTime($c_date_time);
        $c_date = $date->format('M j, Y');
        $c_time = $date->format('g:i A');
    }

    $c_ticket_ID = property_exists($n, 'support_ticket_id') ? $n->support_ticket_id : "-";
    $c_notif_ID = property_exists($n, 'id') ? $n->id : "-";

    // return [$c_empID,$c_sender, $c_notif_type,
    // "<i class='fas fa-trash-alt'></i>",  
    // $c_notes, $c_date ,$c_time,$c_ticket_ID,$c_notif_ID];
    return [$c_empID,$c_sender, $c_notif_type,
    "<i class='fas fa-trash-alt'></i>",
    $c_notes,$c_date,$c_time, $c_ticket_ID, $c_notif_ID
    ];
}














?>