function acceptJobPost(postID, hoID) {
    Swal.fire({
        title: 'Confirm to accept job post?',
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

function declineJobPost(postID, hoID) {

}

function startJobOrder(postID, hoID) {

}

function stopJobOrder(postID, hoID) {

}

// function cancelJobOrder(postID, hoID) {

// }

function paymentReceived(jobOrderID) {

}

function updateCityPreferences(postID, hoID) {

}

function updateSkillset(postID, hoID) {

}

function updateContactInfo(postID, hoID) {

}

function updatePassword(postID, hoID) {

}



