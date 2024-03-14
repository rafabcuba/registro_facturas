var totalCount = 6;
function ChangeIt() {
    var num = Math.ceil(Math.random() * totalCount);
    document.body.background = '../images/login/login' + num + '.jpg';
    //document.body.style.backgroundSize = "cover";// Background repeat
}