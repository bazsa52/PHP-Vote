function nums(){

    outq.innerHTML = "";

    if(qnum.value < 2 || qnum.value > 10){
        alert("Érvénytelen válasz szám!");
        return;
    }

    for(let i = 0; i < qnum.value; i++){
        let tt = document.createElement("input");
        tt.type = "text";
        tt.oninput = sum;
        tt.name = "rr";
        outq.appendChild(tt);
    }

}

function sum(){
    su.value = "";

    let xx = document.getElementsByName("rr");

    xx.forEach(element => {
        su.value += element.value + ";";
    })
    
}