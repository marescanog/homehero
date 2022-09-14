function acceptJobPost(postID, hoID, jobname) {
    Swal.fire({
        title: 'Confirm to accept job post: \"' + jobname + '\"?',
        text: "The corresponding homeowner will be notified with the update as you proceed.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Accept'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: './worker-actions/accept-job-post.php',
                data: { postID: postID, hoID: hoID },
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res["status"] == 200) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Job post accepted and added to ongoing projects! Contact your homeowner for further arrangements.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/ongoingprojects.php';
                        });
                    } else {
                        Swal.fire('Error!', res['message'], 'error').then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/home.php';
                        });
                    }
                },
                error: function (response) {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        }
    })
}

function declineJobPost(postID, hoID, jobname) {
    Swal.fire({
        title: 'Confirm to decline job post: \"' + jobname + '\"?',
        text: "This job post will be deleted from your list and this can action can no longer be reversible.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Decline post',
        cancelButtonText: 'Back',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: './worker-actions/decline-job-post.php',
                data: { postID: postID, hoID: hoID },
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res["status"] == 200) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Job post declined! You may continue to check out the rest of the job posts.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/home.php';
                        });
                    } else {
                        Swal.fire('Error!', res['message'], 'error').then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/home.php';
                        });
                    }
                },
                error: function (response) {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        }
    })
}

function startJobOrder(postID, hoID, jobname) {
    Swal.fire({
        title: 'Confirm to begin the project: \"' + jobname + '\"?',
        text: "The corresponding homeowner will be notified with the update as you proceed.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Start project'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: './worker-actions/start-job-order.php',
                data: { postID: postID, hoID: hoID },
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res["status"] == 200) {
                        Swal.fire({
                            title: 'Success',
                            html: 'Job order now in progress. Work safe!<br>Once you are finish, do not forget to click \"Stop project and generate bill\"!',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/ongoingprojects.php';
                        });
                    } else {
                        Swal.fire('Error!', res['message'], 'error').then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/ongoingprojects.php';
                        });
                    }
                },
                error: function (response) {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        }
    })
}

function stopJobOrder(postID, hoID, jobname, rate) {
    Swal.fire({
        icon: 'info',
        title: 'Generate bill for the project<br>\"' + jobname + '\"',
        text: "Client offer: P"+rate,
        html: `<p>Client offer: P`+rate+`<input type="number" id="val" name="amount" class="swal2-input" placeholder="Enter amount">`,
        confirmButtonText: 'Generate bill',
        showDenyButton: true,
        showCancelButton: true,
        denyButtonText: 'Mark as paid',
        denyButtonColor: '#218838',
        focusConfirm: false,
        preConfirm: () => {
            const amount = Swal.getPopup().querySelector('#val').value
            if (!amount) {
                Swal.showValidationMessage(`Please enter your desired amount.`);
            } else if (amount <= 0) {
                Swal.showValidationMessage(`Please enter a number greater than zero.`);
            }
            return { amount: amount }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var mode = 1;
            $.ajax({
                type: 'POST',
                url: './worker-actions/stop-job-order.php',
                data: { postID: postID, hoID: hoID, mode: mode, amount: result.value.amount },
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res["status"] == 200) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Bill successfully generated! Homeowner already notified.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/pastprojects.php';
                        });
                    } else {
                        Swal.fire('Error!', res['message'], 'error').then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/ongoingprojects.php';
                        });
                    }
                },
                error: function (response) {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        } else if (result.isDenied) {
            var amount = Swal.getPopup().querySelector('#val').value
            if (!amount) {                
                Swal.fire('Error!',`Please enter your desired amount.`,'error');
                return;
            } else if (amount <= 0) {
                Swal.fire('Error!',`Please enter a number greater than zero.`,'error');
                return;
            }
            var mode = 2;
            $.ajax({
                type: 'POST',
                url: './worker-actions/stop-job-order.php',
                data: { postID: postID, hoID: hoID, mode: mode, amount: amount },
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res["status"] == 200) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Bill successfully generated! You may check the project details for the billing information.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/pastprojects.php';
                        });
                    } else {
                        Swal.fire('Error!', res['message'], 'error').then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/ongoingprojects.php';
                        });
                    }
                },
                error: function (response) {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        }
        
    }

    );
}

function paymentReceived(orderID, hoID, amount, jobname) {
    Swal.fire({
        html: '<h4 align="center">Confirm you have received the payment</h4><h3><b>Php ' + amount + '</b></h3><h4>for the project: \"' + jobname + '\"?</h4><br><p>The corresponding homeowner will be notified with the update as you proceed.</p>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirm payment received'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: './worker-actions/payment-received.php',
                data: { orderID: orderID, hoID: hoID },
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res["status"] == 200) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Payment marked as received. Congratulations! You may know proceed to explore more new job posts.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/pastprojects.php';
                        });
                    } else {
                        Swal.fire('Error!', res['message'], 'error').then(result => {
                            window.location = getDocumentLevel() + '/pages/worker/pastprojects.php';
                        });
                    }
                },
                error: function (response) {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        }
    })
}

function updatePersonalInfo() {
    //check info
    var fname = document.querySelector('#inputFName').value;
    var lname = document.querySelector('#inputLName').value;
    var mobile = document.querySelector('#inputMobile').value;
    if(fname === ""){
        Swal.fire('Error!', 'Please enter a valid first name.', 'error');
    } else if (lname === ""){
        Swal.fire('Error!', 'Please enter a valid last name.', 'error');
    } else if (/\d/.test(fname) || /\d/.test(lname)) {
        Swal.fire('Error!', 'Your names cannot contain characters other than letters.', 'error');
    } else if (mobile === "") {
        Swal.fire('Error!', 'Please enter a mobile number.', 'error');
    } else if (mobile.length!=11 || mobile.charAt(0)!='0' || mobile.charAt(1)!='9'){
        Swal.fire('Error!', 'Please enter a valid 11-digit PH mobile number.', 'error');
    } else {
        Swal.fire({
            title: 'Confirm to change your information?',
            text: "This will update your existing name and contact info to the new ones you provide.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm and update'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: './worker-actions/update-personal-info.php',
                    data: { lname:lname,fname:fname,mobile:mobile },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res["status"] == 200) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Your personal info has been changed!',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            }).then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        } else {
                            Swal.fire('Error!', res['message'], 'error').then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        }
                    },
                    error: function (response) {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        })
    }
}

function updateSkillset() {
    //check info
    var skillArray = [];
    for (var x = 1; x <= 6; x++){
        if(document.getElementById('skillcheck'+x).checked){
            skillArray.push(x);
        }
    }
    if(skillArray.length === 0){
        Swal.fire('Error!', 'Please choose at least one skill.', 'error');
    } else {
        Swal.fire({
            title: 'Confirm to change your skillset?',
            text: "This will change the job posts that you can see within this app.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm and update'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: './worker-actions/update-skillset.php',
                    data: { skillArray:skillArray },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res["status"] == 200) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Your skillset has been changed!',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            }).then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        } else {
                            Swal.fire('Error!', res['message'], 'error').then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        }
                    },
                    error: function (response) {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        })
    }
}

function updateCityPreferences() {
    //check info
    var cityArray = [];
    for (var x = 1; x <= 12; x++){
        if(document.getElementById('cityCheck'+x).checked){
            cityArray.push(x);
        }
    }
    if(cityArray.length === 0){
        Swal.fire('Error!', 'Please choose at least one city.', 'error');
    } else {
        Swal.fire({
            title: 'Confirm to change your list of preferred cities?',
            text: "This will change the job posts that you can see within this app.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm and update'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: './worker-actions/update-city-preferences.php',
                    data: { cityArray:cityArray },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res["status"] == 200) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Your city preferences have been changed!',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            }).then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        } else {
                            Swal.fire('Error!', res['message'], 'error').then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        }
                    },
                    error: function (response) {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        })
    }
}

function updatePassword() {
    //check info
    var oldPass = document.querySelector('#oldPass').value;
    var newPass = document.querySelector('#newPass').value;
    var confirmPass = document.querySelector('#confirmPass').value;
    if(oldPass === ""){
        Swal.fire('Error!', 'Please enter your current password.', 'error');
    } else if (newPass === ""){
        Swal.fire('Error!', 'Please enter your new password.', 'error');
    } else if (newPass.length < 8) {
        Swal.fire('Error!', 'Your new password must contain at least 8 characters.', 'error');
    } else if (newPass !== confirmPass) {
        Swal.fire('Error!', 'New password and confirm password does not match!', 'error');
    } else {
        Swal.fire({
            title: 'Confirm to change your password?',
            text: "This action cannot be undone!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm and update'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: './worker-actions/update-password.php',
                    data: { oldPass:oldPass, newPass:newPass, confirmPass:confirmPass },
                    success: function (response) {
                        var res = JSON.parse(response);
                        if (res["status"] == 200) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Your password has been changed!',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            }).then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        } else {
                            Swal.fire('Error!', res['message'], 'error').then(result => {
                                window.location = getDocumentLevel() + '/pages/worker/settings.php';
                            });
                        }
                    },
                    error: function (response) {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        })
    }
}



