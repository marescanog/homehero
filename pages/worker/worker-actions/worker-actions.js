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
                    Swal.fire('Error!', 'Somwthing went wrong. Please try again.', 'error');
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
                    Swal.fire('Error!', 'Somwthing went wrong. Please try again.', 'error');
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
                    Swal.fire('Error!', 'Somwthing went wrong. Please try again.', 'error');
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
                    Swal.fire('Error!', 'Somwthing went wrong. Please try again.', 'error');
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
                    Swal.fire('Error!', 'Somwthing went wrong. Please try again.', 'error');
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
                    Swal.fire('Error!', 'Somwthing went wrong. Please try again.', 'error');
                }
            });
        }
    })
}

function updateCityPreferences(postID, hoID) {

}

function updateSkillset(postID, hoID) {

}

function updateContactInfo(postID, hoID) {

}

function updatePassword(postID, hoID) {

}



