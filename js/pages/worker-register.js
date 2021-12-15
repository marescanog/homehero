const showLoadingOverlay = () => {
    Swal.fire({
        title: "",
        imageUrl: getDocumentLevel()+"/images/svg/Spinner-1s-200px.svg",
        imageWidth: 200,
        imageHeight: 200,
        imageAlt: 'Custom image',
        showCancelButton: false,
        showConfirmButton: false,
        background: 'transparent',
        allowOutsideClick: false
    });
}

const hideLoadingOverlay= () => {
    swal.close();
}

const save_preferred_cities = (form) => {
    console.log("proceeeding to next page...");
    //console.log("Getting the registration session token...");
    //const dataOBJ = getFormDataAsObj(form);
    console.log(form);

    $.ajaxSetup({cache: false})
    $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
        session = data;
        // console.log(session);
        const parsedSession = JSON.parse(session);
        const token = parsedSession['registration_token'];

        // Create new form 
        const samoka = new FormData();

        // Append 
        samoka.append('preferred_cities', form["preferred_cities"]);
        // samoka.append('token', form["preferred_cities"]);
        // console.log("your token is")
        console.log(token);
        // console.log(form);
        // console.log(samoka);

        form['token'] = token;

         // CHANGELINKDEVPROD
        console.log(form);
        $.ajax({
            type : 'POST',
             url : 'http://localhost/slim3homeheroapi/public/registration/save-preferred-cities', // DEV
            // url : 'https://slim3api.herokuapp.com/registration/save-preferred-cities', // PROD (HAS A CORS ISSUE FOR SOME REASON)
            data : samoka,
            contentType: false,
            processData: false,
            headers: {
                "Authorization": `Bearer ${token}`
            },
            success : function(response) {
                console.log(response);
                window.location.href = getDocumentLevel()+"/pages/worker/register.php"+"?page=4";
            },
            error: function(response){
                hideLoadingOverlay();
                console.log(response);
                Swal.fire({
                    icon: 'error 65',
                    title: 'Error on form upload',
                    text: 'Please try again!',
                })
            }
        });
    });
}

const save_preferred_workSchedule = (form) => {
    console.log("proceeeding to next page...");
    //console.log("Getting the registration session token...");
    const dataOBJ = getFormDataAsObj(form);
    console.log(dataOBJ);

    $.ajaxSetup({cache: false})
    $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
        session = data;
        // console.log(session);
        const parsedSession = JSON.parse(session);
        const token = parsedSession['registration_token'];

        // Create new form 
        const samoka = new FormData();

        // Append 
        samoka.append('schedule_preference', dataOBJ["ritem"] == 'ropt1' ? 0 : 1);
        //console.log(dataOBJ["ritem"] == 'ropt1' ? 0 : 1);
        // console.log("your token is")
        // console.log(token);
        //console.log(form);

         // CHANGELINKDEVPROD
        $.ajax({
            type : 'POST',
             url : 'http://localhost/slim3homeheroapi/public/registration/save-general-schedule', // DEV
            // url : 'https://slim3api.herokuapp.com/registration/save-general-schedule', // PROD
            data : samoka,
            contentType: false,
            processData: false,
            headers: {
                "Authorization": `Bearer ${token}`
            },
            success : function(response) {
                console.log(response);
                window.location.href = getDocumentLevel()+"/pages/worker/register.php"+"?page=3";
            },
            error: function(response){
                hideLoadingOverlay();
                console.log(response);
                Swal.fire({
                    icon: 'error 116',
                    title: 'Error on form upload',
                    text: 'Please try again!',
                })
            }
        });
    });
}


const save_SPECIFIC_preferred_workSchedule = (form) => {
    console.log("proceeeding to next page...");
    //console.log("Getting the registration session token...");
    const dataOBJ = getFormDataAsObj(form);
    // console.log(dataOBJ);

    $.ajaxSetup({cache: false})
    $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
        session = data;
        // console.log(session);
        const parsedSession = JSON.parse(session);
        const token = parsedSession['registration_token'];

        // Create new form 
        const samoka = new FormData();

        // Append 
         samoka.append('lead_time', dataOBJ["lead_time"]);
         samoka.append('notice_time', dataOBJ["notice_time"]);

        const DOW = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        // format start time
        for(let x = 0;  x < DOW.length; x++){
            if(dataOBJ["start-time-"+DOW[x]].length != 8){
                dataOBJ["start-time-"+DOW[x]] = dataOBJ["start-time-"+DOW[x]]+':00';
            }
            if(dataOBJ["start-time-"+DOW[x]].length > 8){
                dataOBJ["start-time-"+DOW[x]] = dataOBJ["start-time-"+DOW[x]].substring(0,8);;
            }
            samoka.append('start-time-'+DOW[x], dataOBJ["start-time-"+DOW[x]]);
        }
        // format end time
        for(let x = 0;  x < DOW.length; x++){
            if(dataOBJ["end-time-"+DOW[x]].length != 8){
                dataOBJ["end-time-"+DOW[x]] = dataOBJ["end-time-"+DOW[x]]+':00';
            }
            if(dataOBJ["end-time-"+DOW[x]].length > 8){
                dataOBJ["end-time-"+DOW[x]] = dataOBJ["end-time-"+DOW[x]].substring(0,8);;
            }
            samoka.append('end-time-'+DOW[x], dataOBJ["end-time-"+DOW[x]]);
        }
        // append dayoffs
        for(let x = 0;  x < DOW.length; x++){
            samoka.append('dayoff-input-'+DOW[x], dataOBJ["dayoff-input-"+DOW[x]]);
        }

        console.log(dataOBJ);

        console.log("your token is")
        console.log(token);
        console.log(form);

        //  CHANGELINKDEVPROD
        $.ajax({
            type : 'POST',
             url : 'http://localhost/slim3homeheroapi/public/registration/save-specific-schedule', // DEV
            // url : '', // PROD (No Deployed production link)
            data : samoka,
            contentType: false,
            processData: false,
            headers: {
                "Authorization": `Bearer ${token}`
            },
            success : function(response) {
                console.log(response);
                window.location.href = getDocumentLevel()+"/pages/worker/register.php"+"?page=2";
            },
            error: function(response){
                hideLoadingOverlay();
                console.log(response);
                Swal.fire({
                    icon: 'error 197',
                    title: 'Error on form upload',
                    text: 'Please try again!',
                })
            }
        });

    });
}





const getRegisterSessionToken_then_uploadForm = (form) => {
    console.log("Getting the registration session token...");
    $.ajaxSetup({cache: false})
    $.get(getDocumentLevel()+'/auth/get-register-session.php', function (data) {
        
        session = data;
        console.log(session);
        //console.log(form);
        uploadForm(form, session);
    });
}

const uploadForm = (form, session) => {
    console.log("Then calling app api...")
    console.log(form);
    const parsedSession = JSON.parse(session);
    const token = parsedSession['registration_token'];
    console.log("your token is")
    console.log(token);
    // Create new form 
    const samoka = new FormData();

    // Append all 
    samoka.append('skill_list', form["skill_list"]);
    samoka.append('clearance_no', form["clearance_no"]);
    samoka.append('default_rate', form["default_rate"]);
    samoka.append('default_rate_type', form["default_rate_type"]);
    samoka.append('expiration_date', form["expiration_date"]);

    // Special case if new file has been uploaded
    if(form["file_id"] == "false"){
        samoka.append('file_id', form['file_id']);
        samoka.append('file_name', form["file_name"]);
        samoka.append('file_path', form["file_path"]);
    } else {
        samoka.append('file_id', form["old_file_id"]);
    }

     // CHANGELINKDEVPROD
    // samok ajax di gnhan object kapoy nimu ui
    $.ajax({
        type : 'POST',
        url : 'http://localhost/slim3homeheroapi/public/registration/save-personal-info', // DEV
        // url : 'https://slim3api.herokuapp.com/registration/save-personal-info', // PROD
        data : samoka,
        contentType: false,
        processData: false,
        headers: {
            "Authorization": `Bearer ${token}`
        },
        success : function(response) {
            console.log(response);

            window.location.href = getDocumentLevel()+"/pages/worker/register.php"+"?page=2";

            // // This swal is for dev purposes only below
            // Swal.fire({
            //     title: 'Continue to the Next Modal?',
            //     showDenyButton: true,
            //     showCancelButton: true,
            //     confirmButtonText: 'Continue',
            //     denyButtonText: `Stay`,
            //   }).then((result) => {
            //     if (result.isConfirmed) {
            //         window.location.href = getDocumentLevel()+"/pages/worker/register.php"+"?page=2";
            //     } else if (result.isDenied) {
            //       Swal.fire('Staying here', '', 'info')
            //     }
            //   });
        },
        error: function(response){
            hideLoadingOverlay();
            console.log(response);
            Swal.fire({
                icon: 'error 285',
                title: 'Error on form upload',
                text: 'Please try again!',
            })
        }
    });
}

 // CHANGELINKDEVPROD
const uploadForm_withSingleImage = (form, imageForm) => {
    console.log("Calling google api...");
    $.ajax({
        type : 'POST',
        url : 'http://localhost/IM2/hh-thirdparty/google-cloud-api/upload-single', // DEV
        // url : 'https://hh-thirdparty.herokuapp.com/google-cloud-api/upload-single', // PROD
        data : imageForm,
        contentType: false,
        processData: false,
        // headers: {"Content-Type": "application/x-www-form-urlencoded"},
        success : function(response) {
            console.log(response);
            const file_path = response.response.file_location;
            const file_name = response.response.newFileName;
            // console.log(file_name);
            // console.log(file_path);
            form['file_id'] = 'false';
            form['file_name'] = file_name;
            form['file_path'] = file_path;
            // console.log(form);
            getRegisterSessionToken_then_uploadForm(form);
        },
        error: function(response){
            hideLoadingOverlay();
            console.log(response);
            Swal.fire({
                icon: 'error 319',
                title: 'Error on form upload',
                text: 'Please try again!',
            })
        }
    });
}

// =========================================================
// =========================================================
// =========================================================
// =========================================================
// =========================================================
// =========================================================
// MAIN JS CONTENT HERE ====================================
// =========================================================
// =========================================================
$(document).ready(()=>{
    // Grab the DOM element
    let page = parseInt(document.getElementById("page").value);

    // Validate the value to be in range of pages
    if(page < 0 || page > 4){
        page = 0;
    }

    if(!Number.isInteger(page)){
        page = 0;
    }

    if(page == null || page == undefined || page == ""){
        page = 0;
    }

    // Declare Array for loading contents
    const loadRegisterBody = [
        loadOrientation,
        loadPersonalInfo,
        loadSchedule,
        loadServiceArea,
        loadReview,
    ]

    // Load Content based on Page Number
    loadRegisterBody[page]();
})

// =======================================================================================================
// Page 1
const loadOrientation = () => {
    const level = getDocumentLevel();
    data={}
    data["level"] = level;
    $("#body").load(level+"/components/sections/register-orientation.php", data, ()=>{
        const next = document.getElementById("next");
        next.addEventListener("click", ()=>{
            window.location.href = level+"/pages/worker/register.php"+"?page=1";
        })
    });
}

// =======================================================================================================
// Page 2
const loadPersonalInfo = () => {
    const level = getDocumentLevel();
    data={}
    data["level"] = level;

    $("#body").load(level+"/components/sections/register-personal-info.php", data, ()=>{

        // change image click option
        const change_image = document.getElementById("change-image");
        if(change_image != null){
            change_image.addEventListener("click", ()=>{
                console.log("You clicked on the change image option!");
                $("#file-upload-display").load(level+"/components/forms/change-nbi-image.php", "", ()=>{
                    // nbi file caption change on select
                    const nbi_file_input = document.getElementById("nbi-file-input");
                    if(nbi_file_input != null){
                        $("#detect-nbi-change").on('change','#nbi-file-input' , function(){ 
                            const fileName = nbi_file_input.files[0].name;
                            const label = document.getElementById("label-nbi-file-input");
                            label.innerText = fileName;
                        });
                    }
                });
            });
        }

        // nbi file caption change on select
        const nbi_file_input = document.getElementById("nbi-file-input");
        if(nbi_file_input != null){
            $("#detect-nbi-change").on('change','#nbi-file-input' , function(){ 
                const fileName = nbi_file_input.files[0].name;
                const label = document.getElementById("label-nbi-file-input");
                label.innerText = fileName;
            });
        }

        // Get tomorrow's date
        const today = new Date(); 
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        // Flatpickr settings for the date feild
        flatpickr("#nbi-date-feild", {
            //altInput: true,
            //altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            //minDate: "today",
            minDate: today,
        });


        const button = document.getElementById("PI-submit-btn");
        const buttonTxt = document.getElementById("PI-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("PI-submit-btn-load");

        const back = document.getElementById("back");
        button.addEventListener("click", ()=>{
            // window.location.href = level+"/pages/worker/register.php"+"?page=2";
            $("#personal-info").validate({
                ignore: [],
                rules:{
                    skill_list:{
                        required: true,
                        minlength: 1,
                    },
                    default_rate: {
                        required: true,
                        number: true
                    },
                    clearance_no: "required",
                    expiration_date: {
                        required: true,
                        validDate: true
                    },
                    file: {
                        required: true,
                        extension: "jpeg,jpg,png,pdf,JPEG,JPG,PNG,PDF",
                        filesize: 5000000   //max size 5000 kb 5MB
                    }
                },
                messages:{
                    skill_list: "Please check at least one skill.",
                    default_rate:{
                        required:  "Please enter your rate",
                    },
                    clearance_no: "Please enter your NBI clearance number",
                    expiration_date: {
                        required: "Please enter an expiration date",
                    },
                    file: {
                        required: "Please upload a photo of your NBI clearance.",
                        extension: "Please upload a file with a jpg, png or pdf extension",
                        filesize: "File size must be less than 5 MB"
                    }
                },
                submitHandler: function(form, event) { 
                    event.preventDefault();
                    const formData = getFormDataAsObj(form);
                    const checkedBoxes = document.getElementsByName("skill_list");
                    // Grab all checked items
                    const checkedSkills = []; 
                    checkedBoxes.forEach(check=>{
                        if(check.checked){
                            checkedSkills.push(check.value);
                        }
                    })
                    formData["skill_list"] = checkedSkills;

                    // console.log(checkedSkills);
                    // console.log("Your form data is: ");
                    // console.log(formData); 

                    // Grab the uploaded images (Currently this does not include Certificates. Just the NBI info for now)
                    // Grab all file feilds
                    // const fileFields = document.querySelectorAll("input[type='file']");
                    // console.log(fileFields);

                    // Grab NBI file feild
                    const nbi_feild = document.getElementById("nbi-file-input");
                    // console.log(nbi_feild);

                    showLoadingOverlay();

                    if(nbi_feild == null){
                        // Freeze the form
                        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
                        // Pass to Appropriate function to call API
                        // This api is called when there is no image to upload (No Changes)
                        // it just takes the formData with file_id = "false" and (old_file_id) appended to it.
                            formData["file_id"] = "true";
                            // First need to get registration session token from server and pass it into the AUTH header on ajax request
                            getRegisterSessionToken_then_uploadForm(formData);

                    } else {
                        // Set necessary data for upload image api call
                        const upload_data = {};
                        // Note, this setting has been removed from DB
                        // It only exists in this code now
                        upload_data['file_types'] = ["pdf","jpg","jpeg","png"]; // allowed types of file as per DB (in future make ajax call to grab this)
                        upload_data['bucket_name'] = "nbi-photos"; // location in google cloud/ bucket in cloud

                        // Create new form for the images
                        const imageForm = new FormData();

                        // Append all images to the new form
                        imageForm.append('file', nbi_feild.files[0]);
                        imageForm.append('file_types', JSON.stringify(upload_data['file_types']));
                        imageForm.append('bucket_name', upload_data['bucket_name']);

                        // Freeze the form
                        disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

                        // Pass to Appropriate function to call API
                        // This api is called when there is no image to upload (No Changes)
                        // It requires an ImageForm Object-> contains images only and the form data.
                            uploadForm_withSingleImage(formData, imageForm);
                    }

                    // Swal.fire({
                    //     title: 'Continue to the Next Modal?',
                    //     showDenyButton: true,
                    //     showCancelButton: true,
                    //     confirmButtonText: 'Continue',
                    //     denyButtonText: `Stay`,
                    //   }).then((result) => {
                    //     if (result.isConfirmed) {
                    //         window.location.href = level+"/pages/worker/register.php"+"?page=2";
                    //     } else if (result.isDenied) {
                    //       Swal.fire('Staying here', '', 'info')
                    //     }
                    //   });
                }
            });
        })
        back.addEventListener("click", ()=>{
            window.location.href = level+"/pages/worker/register.php";
        })
    });

}

// =======================================================================================================
// Page 3
const loadSchedule = () => {
    const level = getDocumentLevel();
    // Grab the DOM element for page type
    let edit = document.getElementById("edit").value == 1;
    const outerPageData = {};
    outerPageData["edit"] = edit;

    $("#body").load(level+"/components/sections/register-schedule.php",outerPageData, ()=>{
        const button = document.getElementById("PI-submit-btn");
        const buttonTxt = document.getElementById("PI-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("PI-submit-btn-load");
        const back = document.getElementById("back");
        button.addEventListener("click", ()=>{
            //window.location.href = level+"/pages/worker/register.php"+"?page=3";
            $("#schedule-form").validate({
                ignore: [],
                submitHandler: function(form, event) { 
                    event.preventDefault();
                    // Freeze the form
                    showLoadingOverlay();
                    if(edit){
                        console.log("This is the edit page: You want to save specific schedules");

                        // Format all times




                        save_SPECIFIC_preferred_workSchedule(form);
                    } else {
                        console.log("This is the edit page: You want to save general schedules");
                        save_preferred_workSchedule(form);
                    }
                    disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);
                }
            });
        })
        back.addEventListener("click", ()=>{
            if(edit){
                window.location.href = level+"/pages/worker/register.php"+"?page=2";
            } else {
                window.location.href = level+"/pages/worker/register.php"+"?page=1";
            }
        })

        const rawPretendData = {
            "id": "1",
            "is_monday_off": "0",
            "monday_start_time": "09:00:00", 
            "monday_end_time": "17:00:00",
            "is_tuesday_off": "0",
            "tuesday_start_time": "09:00:00", 
            "tuesday_end_time": "17:00:00",
            "is_wednesday_off": "0",
            "wednesday_start_time": "09:00:00", 
            "wednesday_end_time": "17:00:00",
            "is_thursday_off": "0",
            "thursday_start_time": "09:00:00", 
            "thursday_end_time": "17:00:00",
            "is_friday_off": "0",
            "friday_start_time": "09:00:00", 
            "friday_end_time": "17:00:00",
            "is_saturday_off": "1",
            "saturday_start_time": "09:00:00", 
            "saturday_end_time": "17:00:00",
            "is_sunday_off": "1",
            "sunday_start_time": "09:00:00", 
            "sunday_end_time": "17:00:00"
        }

        // Convert Raw Data to an array
        const arrayData = Object.values(rawPretendData);
        const scheduleData = {};
        const week = [];

        // Fomat the array to an array of objects
        for(x=1; x<arrayData.length;x+=3){
            const day = {};
            day["isDayOff"] = arrayData[x];
            day["start"] = convertFrom24To12Format(arrayData[x+1]);
            day["end"] = convertFrom24To12Format(arrayData[x+2]);
            day["sRaw"] = arrayData[x+1];
            day["eRaw"] = arrayData[x+2];
            week.push(day);
        }

        // move Sunday to front
        rotateRight(week);
       
        // Add the new array as data to be passed into
        scheduleData["week"] = week;
      
        if(edit){
            $("#schedule-preference").load(level+"/components/sections/register-specific-hours.php",scheduleData,()=>{
                // Grab Edit Element for each
                const clickySun = document.getElementById("clicky-Sun");
                const clickyMon = document.getElementById("clicky-Mon");
                const clickyTue = document.getElementById("clicky-Tue");
                const clickyWed = document.getElementById("clicky-Wed");
                const clickyThu = document.getElementById("clicky-Thu");
                const clickyFri = document.getElementById("clicky-Fri");
                const clickySat = document.getElementById("clicky-Sat");
                // Grab DOM for Reset 9-5 and Day off setting
                const reset9to5DOM = document.getElementById("reset9to5Link");
                const setDayOffDOM = document.getElementById("setDayOffLink");
               
                const clickyWeek = [
                    clickySun, clickyMon, clickyTue, clickyWed, clickyThu, clickyFri, clickySat
                ];

                const labelWeek = [
                    "label-Sun", "label-Mon", "label-Tue", "label-Wed", "label-Thu", "label-Fri", "label-Sat"
                ];

                const startInputIds = [
                    "start-time-input-Sun", "start-time-input-Mon", "start-time-input-Tue", "start-time-input-Wed", "start-time-input-Thu", "start-time-input-Fri", "start-time-input-Sat"
                ];

                const toLabelIDs = [
                    "to-Sun", "to-Mon", "to-Tue", "to-Wed", "to-Thu", "to-Fri", "to-Sat"
                ];

                const endInputIds = [
                    "end-time-input-Sun", "end-time-input-Mon", "end-time-input-Tue", "end-time-input-Wed", "end-time-input-Thu", "end-time-input-Fri", "end-time-input-Sat"
                ];

                const applyClickIds = [
                    "appy-click-Sun", "appy-click-Mon", "appy-click-Tue", "appy-click-Wed", "appy-click-Thu", "appy-click-Fri", "appy-click-Sat"
                ];

                const daysBase = [
                    "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"
                ];

                    // Accepts a string Mon - Sun, newStartTime value and newEndTime value
                    // Sets the label & inputs to new time
                    const setNewTime = (dayTxt, newStartTime, newEndTime) => {
                        // Get feilds to change to new values
                        const checkedStart = document.getElementById("start-time-input-"+dayTxt);
                        const label = document.getElementById("label-"+dayTxt);
                        const checkedEnd = document.getElementById("end-time-input-"+dayTxt);
                        const dayOffInput = document.getElementById("dayoff-input-"+dayTxt);

                        // Change Label to new time
                        label.innerText = convertFrom24To12Format(newStartTime) + " - " +
                        convertFrom24To12Format(newEndTime);

                        // Change start time input value to new time
                        checkedStart.setAttribute("value", newStartTime);

                        // Change end time input value to new time
                        checkedEnd.setAttribute("value", newEndTime);

                        // change day off to false
                        dayOffInput.setAttribute("value", "0");
                    }


                    // Accepts a string Mon - Sun
                    // Sets the label & day off inputs to day off
                    // Does not change the previous time values
                    const setDayOff = (dayTxt) => {
                        // Get feilds to change to new values
                        const label = document.getElementById("label-"+dayTxt);
                        const dayOffInput = document.getElementById("dayoff-input-"+dayTxt);

                        // Change Label to Day off
                        label.innerText = "Day off";

                        // change day off to true
                        dayOffInput.setAttribute("value", "1");
                    }

                    // Resets all checked panels back to zero
                    const resetToUncheckAll = () => {
                        daysBase.forEach(dayTxt=>{
                            const checkBox = document.getElementById("chk-"+dayTxt);
                            checkBox.checked = false;
                        });
                    }

                    // Resets all panels back to hide
                    const resetPanels = () => {
                        clickyWeek.forEach( EditClick =>{
                            if(EditClick.innerText == "Apply"){
                                EditClick.innerText = "Edit";
                                let dayType = EditClick.getAttribute("data-day");
                                const thisLabel = document.getElementById(labelWeek[dayType]);
                                const thisApplyClick = document.getElementById(applyClickIds[dayType]);
                                const thisToLabel = document.getElementById(toLabelIDs[dayType]);
                                const thisEndInput = document.getElementById(endInputIds[dayType]);
                                const thisStartInput = document.getElementById(startInputIds[dayType]);
                                thisLabel.classList.remove("d-none");
                                thisStartInput.setAttribute("type", "hidden");
                                thisToLabel.classList.add("d-none");
                                thisEndInput.setAttribute("type", "hidden");
                                thisApplyClick.classList.add("d-none");
                            } else {
                                EditClick.classList.remove("d-none");
                            }                            
                        })
                    }


                 // Add logic for the reset 9-5 clickable item
                 reset9to5DOM.addEventListener("click", ()=>{
                    const myForm = document.getElementById("schedule-form");
                    const formData = getFormDataAsObj(myForm);
                    let changedFeilds = 0;
                    // Reset All input feilds that have the checked attr
                    daysBase.forEach(dayTxt=>{
                        if(formData.hasOwnProperty("chk-"+dayTxt)){
                            // Reset 
                            setNewTime(dayTxt,"09:00:00","17:00:00");
                            changedFeilds++;
                        }
                    });
                    if(changedFeilds==0){
                        alert("No days were selected! Please check on a day.");
                    } else {
                        resetToUncheckAll();
                        resetPanels();
                    }
                });


                // Add logic for the set Days off clickable item
                setDayOffDOM.addEventListener("click", ()=>{
                    const myForm = document.getElementById("schedule-form");
                    const formData = getFormDataAsObj(myForm);
                    let changedFeilds = 0;
                    // Set Days off for input feilds that have the checked attr
                    daysBase.forEach(dayTxt=>{
                        if(formData.hasOwnProperty("chk-"+dayTxt)){
                            // Apply day off 
                            setDayOff(dayTxt);
                            changedFeilds++;
                        }
                    });
                    if(changedFeilds==0){
                        alert("No days were selected! Please check on a day.");
                    } else {
                        resetToUncheckAll();
                        resetPanels();
                    }
                });

                // Set up the Show/Hide for the edit panel of each row in schedule
                clickyWeek.forEach(day=>{
                    // Grab Values & HTML Entitites
                    let dayType = day.getAttribute("data-day");
                    const thisLabel = document.getElementById(labelWeek[dayType]);
                    const thisStartInput = document.getElementById(startInputIds[dayType]);
                    const thisToLabel = document.getElementById(toLabelIDs[dayType]);
                    const thisEndInput = document.getElementById(endInputIds[dayType]);
                    const thisApplyClick = document.getElementById(applyClickIds[dayType]);
                    
                    // Private functions
                    // Show Panel
                    const showEditSchedPanel = () => {
                        // Show the Edit panel
                        day.innerText = "Apply"                            
                        thisLabel.classList.add("d-none");
                        thisStartInput.setAttribute("type", "time");
                        thisToLabel.classList.remove("d-none");
                        thisEndInput.setAttribute("type", "time");
                        thisApplyClick.classList.remove("d-none");
                        // Hide all Edit labels except for current
                        clickyWeek.forEach(editLabel=>{
                            if(day != editLabel){
                                editLabel.classList.add("d-none");
                            }
                        });
                    }
                    // Hide Panel
                    const hideEditSchedPanel = () => {
                        // Hide Edit panel
                        day.innerText = "Edit"                          
                        thisLabel.classList.remove("d-none");
                        thisStartInput.setAttribute("type", "hidden");
                        thisToLabel.classList.add("d-none");
                        thisEndInput.setAttribute("type", "hidden");
                        thisApplyClick.classList.add("d-none");
                        // Show all Edit labels
                        clickyWeek.forEach(editLabel=>{
                            if(day != editLabel){
                                editLabel.classList.remove("d-none");
                            }
                        });
                    }


                    // Get Panel's Current Start Value
                    const newStartTimeFeild = document.getElementById("start-time-input-"+daysBase[dayType]);
                    const newEndTimeFeild = document.getElementById("end-time-input-"+daysBase[dayType]);


                    // Add the logic for "Apply Multiple" clickable item
                    thisApplyClick.addEventListener("click", ()=>{
                        // Grab the HTML DOM elements & values
                        let newStartTime = newStartTimeFeild.value;
                        let newEndTime = newEndTimeFeild.value;
                        const myForm = document.getElementById("schedule-form");
                        const formData = getFormDataAsObj(myForm);
                        let countChanged = 0;
                        daysBase.forEach(dayTxt=>{
                            if(formData.hasOwnProperty("chk-"+dayTxt)){
                                // Set new time
                                setNewTime(dayTxt,newStartTime,newEndTime);
                                countChanged++;
                            }
                        });
                        if(countChanged == 0){
                            alert("No days were selected! Please check on a day.");
                        } else {
                            // Close the Panel
                            hideEditSchedPanel();
                            resetToUncheckAll();
                        }
                    });


                    // Add the logic for "Edit" clickable item
                    day.addEventListener("click", ()=>{
                        if(day.innerText == "Edit"){
                            showEditSchedPanel();
                        } else if (day.innerText == "Apply"){
                            // Apply for only this selected panel
                            let thisdayTxt = daysBase[dayType];
                            let newStartTime = newStartTimeFeild.value;
                            let newEndTime = newEndTimeFeild.value;
                            // Get feilds to change to new values
                            const label = document.getElementById("label-"+thisdayTxt);
                            const dayOffInput = document.getElementById("dayoff-input-"+thisdayTxt);

                            // Change Label to new time
                            label.innerText = convertFrom24To12Format(newStartTime) + " - " +
                            convertFrom24To12Format(newEndTime);

                            // change day off to false
                            dayOffInput.setAttribute("value", "0");
                            hideEditSchedPanel();
                        }
                    });
                });

                

                // const clicky = document.getElementById("clicky");
                // clicky.addEventListener("click", ()=>{

                // })
            });
        } else {
            $("#schedule-preference").load(level+"/components/sections/register-general-schedule.php",scheduleData,()=>{
                const clicky = document.getElementById("clicky");
                clicky.addEventListener("click", ()=>{
                    window.location.href = level+"/pages/worker/register.php"+"?page=2&edit=true";
                })
            });
        }
    });
}

// Page 3
const loadServiceArea = () => {
    const level = getDocumentLevel();
    $("#body").load(level+"/components/sections/register-service-area.php", ()=>{
        const button = document.getElementById("PI-submit-btn");
        const buttonTxt = document.getElementById("PI-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("PI-submit-btn-load");
        const back = document.getElementById("back");
        
        button.addEventListener("click", ()=>{
           // window.location.href = level+"/pages/worker/register.php"+"?page=4";
           $("#city-preference").validate({rules:{
            chk_cities:{
                required: true,
                minlength: 1,
            },
        },
        messages:{
            chk_cities:{
                required:  "Please select at lease one city.",
            },
        },
            submitHandler: function(form, event) { 
                event.preventDefault();

                const formData = getFormDataAsObj(form);
                const checkedBoxes = document.getElementsByName("chk_cities");
                // Grab all checked items
                const checkedCities = []; 
                checkedBoxes.forEach(check=>{
                    if(check.checked){
                        checkedCities.push(check.value);
                    }
                })
                formData["preferred_cities"] = checkedCities;
                //console.log(formData)


                // (DEV BUILD UNCOMMENT)
                // (DEV BUILD UNCOMMENT)
                // Submit the form (DEV BUILD UNCOMMENT)
                save_preferred_cities(formData);

                showLoadingOverlay();
                // Freeze the form
                disableForm_displayLoadingButton(button, buttonTxt, buttonLoadSpinner, form);

                // (PROD BUILD)
                // (PROD BUILD UNCOMMENT - CANNOT PROCEED DUE TO CORS)
                // window.location.href = level+"/pages/worker/register.php"+"?page=4";

            }
            });
        })
        back.addEventListener("click", ()=>{
            window.location.href = level+"/pages/worker/register.php"+"?page=2";
        })
    });
}

// Page 4
const loadReview = () => {
    const level = getDocumentLevel();
    $("#body").load(level+"/components/sections/register-review.php", ()=>{
        const button = document.getElementById("PI-submit-btn");
        const buttonTxt = document.getElementById("PI-submit-btn-txt");
        const buttonLoadSpinner = document.getElementById("PI-submit-btn-load");
        const back = document.getElementById("back");
        button.addEventListener("click", ()=>{
            // window.location.href = level+"/pages/worker/completed-registration.php";

            // Enable Modal Overalay and disable button
            disable_button_enableModalSpinner(button, buttonTxt, buttonLoadSpinner);
            showLoadingOverlay();

            // Do an ajax call to save the "Registered option into worker and make a support ticket"
            // For worker verification
            $.ajax({
                type : 'GET',
                url : getDocumentLevel()+'/auth/complete-register-auth.php',
                success : function(response) {
                    var res = JSON.parse(response);
                    // Your response after register-auth is
                    console.log(res)
                    if(res["status"] == 200){
                        window.location.href = level+"/pages/worker/completed-registration.php";
                        hideLoadingOverlay();
                    } else {
                        hideLoadingOverlay();
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong! Please try again',
                            icon: 'error',
                            confirmButtonText: 'ok'
                        })
                    }
                }
            });
        });
        back.addEventListener("click", ()=>{
            window.location.href = level+"/pages/worker/register.php"+"?page=3";
        });

        // Event listeners for the Edit links
        const edit_credentials = document.getElementById("edit-credentials");
        const edit_hours= document.getElementById("edit-hours");
        const edit_area = document.getElementById("edit-area");

        edit_credentials.addEventListener("click", ()=>{
            window.location.href = level+"/pages/worker/register.php"+"?page=1";
        });

        edit_hours.addEventListener("click", ()=>{
            window.location.href = level+"/pages/worker/register.php"+"?page=2";
        });

        edit_area.addEventListener("click", ()=>{
            window.location.href = level+"/pages/worker/register.php"+"?page=3";
        });
    });
}