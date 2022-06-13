$("#header-search-form").validate({
    rules: {
        header_search:{
            required: true
        }
    },
    messages: {
        header_search:{
            required: ""
        }
    },
    submitHandler: function(form, event) { 
        event.preventDefault();
        console.log("test");
        const submitformData = getFormDataAsObj(form);
        window.location = './ticket.php?id='+submitformData.header_search;
    }
});
