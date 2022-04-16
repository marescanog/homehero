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
    $c_lname =property_exists($n, 'last_name') ? $n->last_name[0]."." : "";
    $c_agent =property_exists($n, 'first_name') ? $n->first_name." ".$c_lname : "-";

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
?>