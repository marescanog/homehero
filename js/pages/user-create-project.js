$(document).ready(()=>{

    load_create_project_form();



    // var form = document.getElementById("form-home");
    // var button = document.getElementById("button-home");

    // // console.log(form);
    // // console.log(button);

    // form.setAttribute("onSubmit", "submitForm(event)");

    // button.addEventListener("click", ()=>{
    //     console.log("button has been clicked");  
    //     // const modalTypes = {
    //     // "login" : "../../components/modals/temp-enter-address.php",
    //     // "error" : "../../components/modals/error.php" 
    //     // }
    //     // loadModal("login", modalTypes);
    // });
})

const pages = [
    getDocumentLevel()+"/", 
    getDocumentLevel()+"/components/forms/UCPFC-1.php",
    getDocumentLevel()+"/components/forms/UCPFC-2.php",
    getDocumentLevel()+"/components/forms/UCPFC-3.php"
]

const load_create_project_form = (
    current_page = 1, 
    page1_Data = null, 
    page2_Data = null, 
    page3_Data = null,
) => {
    // let obj = {};
    // obj['level'] = getDocumentLevel();
    // obj["current_page"] = current_page;
    // if(page1_Data != null){
    //     obj["page_1"] = page1_Data;
    // }
    // if(page2_Data != null){
    //     obj["page_2"] = page2_Data;
    // }
    // if(page3_Data != null){
    //     obj["page_3"] = page3_Data;
    // }

    $("#user-create-project-form").load(getDocumentLevel()+"/components/forms/user-create-project-form.php",
    {
        current_page: current_page,
        // page_1: page1_Data,
        // page_2: page2_Data,
        // page_3: page3_Data, 
        level: getDocumentLevel(),
    }, 
    ()=>{
        $("#form-content-display").load(pages[current_page],
        {
            current_page: current_page,
            page_1: page1_Data,
            page_2: page2_Data,
            page_3: page3_Data, 
            level: getDocumentLevel(),
        },
        ()=>{
            // Grab the DOM elements
            // Next Buttons
            const button_page1 = document.getElementById("btn-page-1");
            const button_page2 = document.getElementById("btn-page-2");
            const button_page3 = document.getElementById("btn-page-3");
            // Back Buttons
            const button_back_page2 = document.getElementById("btn-back-page-2");
            const button_back_page3 = document.getElementById("btn-back-page-3");

            // Forward to Page 2
            if(button_page1 != null){
                button_page1.addEventListener("click", ()=>{
                    const text1 = document.getElementById("text1");
                    let page1Data = {};
                    page1Data['text1'] = text1.value;

                    const text2 = document.getElementById("text2");
                    let page2Data = {};
                    page2Data['text2'] = text2.value;

                    const text3 = document.getElementById("text3");
                    let page3Data = {};
                    page3Data['text3'] = text3.value;

                    load_create_project_form(2, page1Data, page2Data, page3Data);
                })
            }

            // Backward to Page 1
            if(button_back_page2 != null){
                button_back_page2.addEventListener("click", ()=>{
                    const text1 = document.getElementById("text1");
                    let page1Data = {};
                    page1Data['text1'] = text1.value;

                    const text2 = document.getElementById("text2");
                    let page2Data = {};
                    page2Data['text2'] = text2.value;

                    const text3 = document.getElementById("text3");
                    let page3Data = {};
                    page3Data['text3'] = text3.value;

                    load_create_project_form(1, page1Data, page2Data, page3Data);
                });
            }

            // Forward to Page 3
            if(button_page2 != null){
                button_page2.addEventListener("click", ()=>{
                    const text1 = document.getElementById("text1");
                    let page1Data = {};
                    page1Data['text1'] = text1.value;

                    const text2 = document.getElementById("text2");
                    let page2Data = {};
                    page2Data['text2'] = text2.value;

                    const text3 = document.getElementById("text3");
                    let page3Data = {};
                    page3Data['text3'] = text3.value;

                    load_create_project_form(3, page1Data, page2Data, page3Data);
                })
            }

            // Backward to Page 2
            if(button_back_page3 != null){
                button_back_page3.addEventListener("click", ()=>{
                    const text1 = document.getElementById("text1");
                    let page1Data = {};
                    page1Data['text1'] = text1.value;

                    const text2 = document.getElementById("text2");
                    let page2Data = {};
                    page2Data['text2'] = text2.value;

                    const text3 = document.getElementById("text3");
                    let page3Data = {};
                    page3Data['text3'] = text3.value;

                    load_create_project_form(2, page1Data, page2Data, page3Data);
                });
            }

            // Submit the form
            if(button_page3 != null){
                button_page3.addEventListener("click", ()=>{
                    const myForm = document.getElementById("form-submission-create-project");
                    
                    console.log("Submitted!");  

                    // Convert Form Data to Object
                    let formData = new FormData(myForm);
                    let data = {};
                    formData.forEach((value, key) => data[key] = value);

                    $.ajax({
                        type: 'POST',
                        url : 'http://localhost/slim3homeheroapi/public/job-post/create',
                        data : data,
                        success : function(response) {
                            console.log(response);
                        },
                        error : function(response) {
                            console.log(response);
                        },
                    });

                })
            }
        })

        // Grab the DOM elements
        // var myForm = document.getElementById("form-submission-create-project");
        // var formContentDisplay = document.getElementById("form-content-display");

        // // Buttons
        // var button_page1 = document.getElementById("btn-page-1");
        // var button_page2 = document.getElementById("btn-page-2");
        // var button_page3 = document.getElementById("btn-page-3");
        // var button_back_page2 = document.getElementById("btn-back-page-2");
        // var button_back_page3 = document.getElementById("btn-back-page-3");

        // // Input Feilds
        // var text1 = document.getElementById("text1");
        // var text2 = document.getElementById("text2");
        // var text3 = document.getElementById("text3");

        // // Forward to Page 2
        // button_page1.addEventListener("click", ()=>{
        //     let page1Data = {};
        //     page1Data['text1'] = text1.value;

        //     let page2Data = {};
        //     page1Data['text2'] = text2.value;

        //     let page3Data = {};
        //     page1Data['text2'] = text3.value;
            
        //     if(text3.value != ""){
        //         console.log("I go forward with page 1, 2 and 3 data");
        //         return;
        //     }

        //     if(text2.value != ""){
        //         console.log("I go forward with page 1 and 2 data");
        //         load_create_project_form( 2, page1Data, {adasdsads:  text2.value});
        //         return;
        //     }

        //     console.log("I go forward with page 1 data only");
        //     load_create_project_form(2, page1Data);
        // });

        //  // Back to Page 1
        //  button_back_page2.addEventListener("click", ()=>{

        //     let page1Data = {};
        //     page1Data['text1'] = text1.value;

        //     let page2Data = {};
        //     page1Data['text2'] = text2.value;

        //     let page3Data = {};
        //     page1Data['text2'] = text3.value;

        //     if(text3.value != ""){
        //         console.log("I go back with page 1, 2 and 3 data");
        //         return;
        //     }

        //     if(text2.value != ""){
        //         console.log("I go back with page 1 and 2 data");
        //         load_create_project_form( 1, page1Data, page2Data);
        //         return;
        //     }

        //     console.log("I go back with page 1 data only");
        //     load_create_project_form( 1, page1Data);
        
        //     // let page1Data = {};
        //     // page1Data['text1'] = text1.value;
            
        //     // load_create_project_form(2, page1Data);
        // });

        // button_page2.addEventListener("click", ()=>{
        //     let page1Data = {};
        //     page1Data['text1'] = text1.value;

        //     let page2Data = {};
        //     page2Data['text2'] = text2.value;
        //     load_create_project_form(3, page1Data, page2Data);
        // });

        // button_page3.addEventListener("click", ()=>{
        //     console.log("Submitted!");  

        //     // Convert Form Data to Object
        //     let formData = new FormData(myForm);
        //     let data = {};
        //     formData.forEach((value, key) => data[key] = value);

        //     $.ajax({
        //         type: 'POST',
        //         url : 'http://localhost/slim3homeheroapi/public/job-post/create',
        //         data : data,
        //         success : function(response) {
        //             console.log(response);
        //         },
        //         error : function(response) {
        //             console.log(response);
        //         },
        //     });
        // });
    });
}

// const submitForm = (e)=>{
//     e.preventDefault();


    
// }