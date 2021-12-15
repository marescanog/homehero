// console.log("laodModal.js is loaded!");
const docLevelModal = getDocumentLevel();

const modalTypes = {
    "clear" : docLevelModal+"/components/modals/empty.php",
    "signup" : docLevelModal+"/components/modals/homeowner-register.php",
    "worker-signup" : docLevelModal+"/components/modals/worker-register.php",
    "error" : docLevelModal+"/components/modals/error.php" ,
    "check-avail-est-date" : docLevelModal+"/components/modals/check-avail-est-date.php" ,
    "check-avail-exact-date" : docLevelModal+"components/modals/check-avail-exact-date.php",
    "user-login":  docLevelModal+"/components/modals/homeowner-login.php",
    "worker-login":  docLevelModal+"/components/modals/worker-login.php",
    "tempAddr" : docLevelModal+"/components/modals/temporary/temp-enter-address.php", 
    "tempRegister": docLevelModal+"/components/modals/temporary/test-modal-register.php",
    "tempLogin": docLevelModal+"/components/modals/temporary/temp-login.php",
    "SMS-verification":  docLevelModal+"/components/modals/sms-verification.php",
    "SMS-verification-worker":  docLevelModal+"/components/modals/sms-verification-worker.php",
    "SMS-verification-homeowner":  docLevelModal+"/components/modals/sms-verification.php",
    "edit-project":  docLevelModal+"/components/modals/ho-edit-project.php",
    "cancel-project":  docLevelModal+"/components/modals/ho-project-cancel.php",
    "cancel-post":  docLevelModal+"/components/modals/ho-post-cancel.php",
    "template": docLevelModal+"/components/modals/temporary/template.php",
    "cancel-and-repost":  docLevelModal+"/components/modals/ho-project-cancel-and-repost.php",
    "report-worker":  docLevelModal+"/components/modals/ho-project-report-worker.php",
    "report-problem":  docLevelModal+"/components/modals/ho-project-report-problem.php",
    "reschedule":  docLevelModal+"/components/modals/ho-project-reschedule.php",
    "report":  docLevelModal+"/components/modals/ho-project-report.php",
    "rate":  docLevelModal+"/components/modals/ho-project-rate.php",
    "report-bill":  docLevelModal+"/components/modals/ho-project-report-bill.php",
    "home-enter-address": docLevelModal+"/components/modals/ho-home-enter-address.php",
    "home-your-address": docLevelModal+"/components/modals/ho-home-your-address.php",
    "home-select-address": docLevelModal+"/components/modals/ho-home-select-address.php",
    "editAddr" : docLevelModal+"/components/modals/temporary/temp-edit-address.php", 
    "profile-edit-name" : docLevelModal+"/components/modals/ho-profile-edit-name.php", 
    "profile-add-picture" : docLevelModal+"/components/modals/ho-profile-add-picture.php", 
    "change-phone-sms": docLevelModal+"/components/modals/sms-verification-to-change-ho-pass.php",
    "notify-worker": docLevelModal+"/components/modals/notify-worker.php"
}

// This function loads the
const loadModal = (modalType, modalTypes, modalFunction = () => {}, level=".", data = {}) => {
    // const modalTypes = {
    //     "name" : "location",
    // }
    if(modalTypes.hasOwnProperty(modalType)){
        // create an empty object
        let obj = {};
        obj['level'] = level;
        obj['data'] = data;
        $("#modal-contents").load(modalTypes[modalType], obj, ()=>{
            modalFunction();
        });
    } else {
        $("#modal-contents").load(modalTypes["error"]);
    }
}



