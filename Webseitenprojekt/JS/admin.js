/**
 * adds userlist item to the user list
 * @param {string} nick 
 * @param {strimng} access 
 */
function addUserListItem( nick, access ){

     const item =createListElement("form","", "","","itemForm listRow ", false);
     item.setAttribute("method","post");

     const nickname = createListElement("input","text",nick,"nick","", true);
  
     const accessLabel =  document.createElement("label");
     let you = "";
     if(nickName === nick)
     you = "(you)";
     accessLabel.innerText = "➜"+access+you;
     accessLabel.style.minWidth ="6rem";

     let userAction = "User sperren";
     if(access === "locked")
        userAction = "User entsperren";

     const lockButton =  createListElement("input","submit", userAction,"enter","", false);
     const deleteButton = createListElement("input","submit", "User löschen","enter","warning", false);
   
     if(access === "admin"){
        lockButton.style.visibility="hidden";
        deleteButton.style.visibility="hidden";
     }
    
     item.appendChild(nickname);
     item.appendChild(accessLabel);
     item.appendChild(lockButton);
     item.appendChild(deleteButton);
  
     document.getElementById("userList").appendChild(item);
}

/**
 * adds productlist item to the products list
 * @param {string} productId 
 */

function addProductListItem( product ){


    const item = createListElement("div","", "","","listRow",false);
    
    const itemForm =  createListElement("form","", "","","itemForm noBorder",false);
    itemForm.setAttribute("method","post");

    const image = createListElement("img");
    image.src = product.image;
    image.style.width = "2rem";
    const productid = createListElement("input","text",product.productId,"productId","", true);
    const deleteButton = createListElement("input","submit", "Produkt löschen","enter","warning", false);

    const changeButton = document.createElement('button');
    changeButton.className ="productButton";
    changeButton.innerText = "Details ändern";

    changeButton.addEventListener("click", function(){ changeProduct( product )});
    
    itemForm.appendChild(productid);
    itemForm.appendChild(deleteButton);

     item.appendChild(image);
    item.appendChild(itemForm);
    item.appendChild(changeButton);
  
    document.getElementById("productList").appendChild(item);
}

/**
 * Creates List Item
 * @param {object} elemType 
 * @param {string} type 
 * @param {string} value 
 * @param {string} name 
 * @param {string} className 
 * @param {boolean} readonly 
 * @return {object} 
 */
function createListElement( elemType, type, value, name, className, readonly){

    const elem = document.createElement(elemType);

    if(value != "")
        elem.value = value;

    if(type != "")
      elem.type = type; 

    if(name != "")   
       elem.name = name;

    if(className != "")
        elem.className = className;

    if(readonly)
        elem.setAttribute("readonly","");

    return elem;
}

function changeProduct( product ){

     document.querySelector("#product h3").innerText = "Produkt ändern";  
     document.querySelector("#productId").value = product.productId;
    // document.querySelector("#productId").disabled=true;
     document.querySelector("#name").value = product.name;
     document.querySelector("#description").value = product.description;
     document.querySelector("#price").value = product.price;

     if(product.isConsumeable == "1")
            document.querySelector("#isConsumeable").setAttribute("checked","");
     else
            document.querySelector("#isConsumeable").removeAttribute("checked");

     document.querySelector("#product input[type='submit']").value = "Produkt ändern";

     document.querySelector("#addProductButton").style.display = "block";
}

function addProduct(){

    document.querySelector("#product h3").innerText = "Produkt hinzufügen";  
    document.querySelector("#productId").value = "";
    document.querySelector("#productId").disabled=false;
    document.querySelector("#name").value ="";
    document.querySelector("#description").value = "";
    document.querySelector("#price").value = "";
    document.querySelector("#isConsumeable").removeAttribute("checked");
    document.querySelector("#product input[type='submit']").value = "Produkt hinzufügen";

    document.querySelector("#addProductButton").style.display = "none";
}


