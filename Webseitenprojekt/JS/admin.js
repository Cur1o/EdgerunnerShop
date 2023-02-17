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