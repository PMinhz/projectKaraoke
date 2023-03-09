'use strict'

const avatarPersonal = document.querySelector('.js-items-icon');
const showaMenuAvatarPersonal = document.querySelector('.js-icon-setting-show');
const bellHeader = document.querySelector('.js-hide-bell-header');
const bellShowHeader = document.querySelector('.js-icon-bell-show');
const iconCloseBellNotify = document.querySelector('.js-close-bell-notify')
const showSearchHeader = document.querySelector('.js-hide-search-header')
const searchHeader = document.querySelector('.js-search-header');
const searchStopHeader = document.querySelector('.js-search-header');
const showExport = document.querySelector('.js-show-file-type');
const chooseExport = document.querySelector('.js-choose-file-type');

const closeModalSeeUser = document.querySelector('.js-modal-see-user');
const stopModalSeeUser = document.querySelector('.js-modal-user-detail');
const closeModalEditUser = document.querySelector('.js-modal-edit-user')
const stopModalEditUser = document.querySelector('.js-modal-edit-detail')
const showModalComfirmPassword = document.querySelector('.js-modal-confirm-password')
const stopModalComfirm = document.querySelector('.js-modal-confirm')
const closeModalSaveInfor = document.querySelector('.js-modal-save-infor')
const closeModalDeleteUser = document.querySelector('.js-modal-delete-user');
const closeModalPb = document.querySelector('.js-modal-add-pb')
const closeModalEditPb = document.querySelector('.js-modal-edit-pb')

const body = document.querySelector('body')

    // avatar setting

    function showMenuAvatar(event){
        showaMenuAvatarPersonal.classList.toggle('active');
        event.stopPropagation()
    }

    function hideMenuAvatar(){
        showaMenuAvatarPersonal.classList.remove('active');
    }

    avatarPersonal.addEventListener('click',showMenuAvatar);
    body.addEventListener('click',hideMenuAvatar);
    showaMenuAvatarPersonal.addEventListener('click', (event) =>{
        event.stopPropagation();
    })


    // notification

    function showBellHeader(event){
        bellShowHeader.classList.toggle('active');
        event.stopPropagation();
    }

    // search header
    function showsSearchHeader(event){
        searchHeader.classList.toggle('active');
        event.stopPropagation();
    }
    const iconSearchClose = document.querySelector('.icon-search-close');
    const searchHeaderInput = document.querySelector('.input-search-header');


// Jquery
$(document).ready(function() {
    // menu dropdown
    $('.list-items-user ul').hide();
    $(".list-items-user a").click(function () {
        $(this).parent(".list-items-user").children("ul").slideToggle("2000");
        $('.arrow-right-user').toggleClass('arrow-down')
    });

    $('.list-items-task ul').hide();
    $(".list-items-task a").click(function () {
        $(this).parent(".list-items-task").children("ul").slideToggle("2000");
        $('.arrow-right-task').toggleClass('arrow-down')
    });

});

// modal data
var model_del_id = ''
var modal_page =''
var modal_type =''
var del_id_phong =''
    function showModalComfirmPass(data){
        showModalComfirmPassword.classList.add('active')
        document.getElementById("resetpassword-user").innerText = data['fullname']
        model_del_id = data['id_user']
        modal_page = data['page']
        modal_type = data['type']
    }
    function closeModalComfirmPass(){
        showModalComfirmPassword.classList.remove('active')
    }

    function respass_modal()
    {
        document.cookie = "preventres = 1"
        document.location.href = './listuser.php?page='+modal_page+'&type='+modal_type+'&resid='+model_del_id;
    }

    if(stopModalComfirm !== null){
        stopModalComfirm.addEventListener('click',(e) =>{
            e.stopPropagation();
        })
    }

    function showModalSaveInfor(){
        closeModalSaveInfor.classList.add('active')
    }

    function closeModalSaveinfor(){
        closeModalSaveInfor.classList.remove('active')
    }

    function showModalDeleteUser(data){
        closeModalDeleteUser.classList.add('active')
        document.getElementById("modal-delete").innerHTML = data['fullname'];
        model_del_id = data['id_user']
        modal_page = data['page']
        modal_type = data['type']
    }
    function showModalDeletehoadon(data){
        closeModalDeleteUser.classList.add('active')
        model_del_id = data['id_hoadon']
        modal_page = data['page']
    }

    function showModalDeleteDH(data){
        closeModalDeleteUser.classList.add('active')
        document.getElementById("modal-delete").innerHTML = data['tenphong'];
        model_del_id = data['id_dondat']
        del_id_phong = data['id_phong']
        modal_page = data['page']
    }

    function showModalconfirm(data){
        showModalComfirmPassword.classList.add('active')
        document.getElementById("resetpassword-user").innerHTML = data['tenphong']
        model_del_id = data['id_dondat']
        modal_page = data['page']
    }

    function del_modal()
    {
        document.cookie = "preventdel = 1"
        document.location.href = './listuser.php?page='+modal_page+'&type='+modal_type+'&delid='+model_del_id;
    }
    
    function del_hoadon()
    {
        document.cookie = "preventdel = 1"
        document.location.href = './doanhthu.php?delid='+model_del_id;
    }

    function del_modalnv()
    {
        document.cookie = "preventdel = 1"
        document.location.href = './nhanvienduyet.php?page='+modal_page+'&delid='+model_del_id+'&delidphong='+del_id_phong;
    }

    function confirm_modalnv()
    {
        document.cookie = "preventcon = 1"
        document.location.href = './nhanvienduyet.php?page='+modal_page+'&confirm='+model_del_id;
    }

    function closeModalDeleteuser(){
        closeModalDeleteUser.classList.remove('active')
    }

    function closeModalDeleteDH(){
        closeModalDeleteUser.classList.remove('active')
    }

    function closeModalconfirm(){
         showModalComfirmPassword.classList.remove('active')
    }

    function showModalPb(){
        closeModalPb.classList.add('active')
    }   

    function closeModalpb(){
        closeModalPb.classList.remove('active')
    }  

    function showModalEditPb(){
        closeModalEditPb.classList.add('active')
    }

    function closeModalEditpb(){
        closeModalEditPb.classList.remove('active')
    }
    
if(showExport !== null && chooseExport !== null)
{
    function showMenuExport(event){
        chooseExport.classList.toggle('active');
        event.stopPropagation()
    }

    function hideMenuExport(){
        chooseExport.classList.remove('active');
    }


    showExport.addEventListener('click',showMenuExport)
    body.addEventListener('click',hideMenuExport)
    avatarPersonal.addEventListener('click',hideMenuExport)
    showExport.addEventListener('click',(event) =>{
        event.stopPropagation();
    });
}
function checkbox(parent,element,input)
{
    if (element.checked)
    {
        parent.style.boxShadow = "0px 5px 10px rgba(185, 185, 185, 0.4)";
        element.checked = false;
        input.disabled = true;
        input.value = 0;
    }
    else
    {
        parent.style.boxShadow = "0px 2px 10px rgb(43, 255, 0) ";
        element.checked = true;
        input.disabled = false;
        input.value = 1;
    }
}
function showModalSeeUser(data){
    closeModalSeeUser.classList.add('active');
    document.getElementById("modal-name").innerHTML = data['fullname'];
    document.getElementById("modal-Chucvu").innerHTML = document.getElementById("list-chucvu").textContent.replace(/\s/g, '');
    document.getElementById("modal-CMND").innerHTML = data['cmnd'];
    document.getElementById("modal-SDT").innerHTML = data['sdt'];
    document.getElementById("modal-email").innerHTML = data['email'];
    document.getElementById("modal-diachi").innerHTML = data['address'];
    document.getElementById("modal-username").innerHTML = data['username'];
}

function showModalSeehoadon(data){
    closeModalSeeUser.classList.add('active');
    document.getElementById("modal-CMND").innerHTML = data['cmnd'];
    document.getElementById("modal-SDT").innerHTML = data['sdt'];
    document.getElementById("modal-sudung").innerHTML = data['gio'];
    document.getElementById("modal-monan").innerHTML = data['tienmon']+"K";
    document.getElementById("modal-phong").innerHTML = data['tienphong']+"K";
}

function showModalSeeDH(data){
    closeModalSeeUser.classList.add('active');
    document.getElementById("modal-tenphong").innerHTML = data['tenphong'];
    document.getElementById("modal-name").innerHTML = data['hoten'];
    document.getElementById("modal-CMND").innerHTML = data['cmnd'];
    document.getElementById("modal-SDT").innerHTML = data['sdt'];
    document.getElementById("modal-username").innerHTML = data['username'];
    document.getElementById("modal-date").innerHTML = data['ngay'];
    document.getElementById("modal-time").innerHTML = data['gio'];
     //father element
    let container = document.getElementById("container-monan");
    if(data['mangmon'].length==0)
    {
        //creat elemnt
        let div = document.createElement('div');
        let label = document.createElement('Label');
        //set text
        div.classList.add("form-input-see");
        label.innerHTML = "Không đặt món"
         //append
        div.appendChild(label)
        container.appendChild(div)
    }
    else
    {
        data['mangmon'].forEach(e =>
        { 
        let arrsplit = e.split("?")
         //creat elemnt
        let div = document.createElement('div');
        let label = document.createElement('Label');
        let h6 = document.createElement('H6');
        //set text
        div.classList.add("form-input-see");
        h6.classList.add( "h6-modal-monan");
        label.innerHTML = arrsplit[2]
        h6.innerHTML = "số Lượng: "+arrsplit[0]
        //append
        div.appendChild(label)
        div.appendChild(h6)
        container.appendChild(div)
        }
        )
    }

}
function closeModalSeeuser(){
    closeModalSeeUser.classList.remove('active');
    let container = document.getElementById("container-monan");
    while (container.firstChild) {
        container.removeChild(container.lastChild);
      }
}
function closeModalSeeDH(){
    closeModalSeeUser.classList.remove('active');
    let container = document.getElementById("container-monan");
    while (container.firstChild) {
        container.removeChild(container.lastChild);
      }
}
if(stopModalSeeUser !== null){
    stopModalSeeUser.addEventListener('click', (e) =>{
        e.stopPropagation();
    })
}
function closeModalSeeIcon(){
    closeModalSeeUser.classList.remove('active');
}

