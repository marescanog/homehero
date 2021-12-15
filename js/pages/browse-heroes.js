console.log('browse heroes js');

const openModal=($userID)=>{

    const data = {};
    data['worker_id'] = $userID;

    loadModal("notify-worker", modalTypes,()=>{
        const mySelect = document.getElementById('project_select');
        const allCards = document.getElementsByClassName("cardTastic");
        const cardArr = Array.from(allCards);

        mySelect.onchange = (event) => {
            let jp_no = event.target.value;
            // console.log(jp_no);
            // console.log(x);
            // Disable all cards from showing
            for(xx = 0 ; xx < cardArr.length; xx++){
                if(!cardArr[xx].classList.contains("d-none")){
                    cardArr[xx].classList.add("d-none");
                }
            }
            // Disable enable the selected card
            let cardID = "post-details-"+jp_no;
            let zecard = document.getElementById(cardID);
            if(zecard != null){
                zecard.classList.remove("d-none");
            }
        }
    }, getDocumentLevel(), data);
    $('#modal').modal('show');
}