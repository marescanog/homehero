const convertFrom24To12Format = (time24) => {
    const [sHours, minutes] = time24.match(/([0-9]{1,2}):([0-9]{2})/).slice(1);
    const period = +sHours < 12 ? 'AM' : 'PM';
    const hours = +sHours % 12 || 12;
  
    return `${hours}:${minutes} ${period}`;
}

function rotateRight(arr){
    let last = arr.pop();
    arr.unshift(last);
    return arr;
}

const getFormDataAsObj = (myForm) =>{
    let formData = new FormData(myForm);
    let data = {};
    formData.forEach((value, key) => data[key] = value);
    return data;
}