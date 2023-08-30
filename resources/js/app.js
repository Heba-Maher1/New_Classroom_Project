import './bootstrap';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();
if(classroomId){
    Echo.private('classroom.' + classroomId)
    .listen('.classwork-created' , function(event){ // when customize the name of the event we must add . before the name of the evenet
        alert(event.title);
    });

}

Echo.private('Notifications.' + userId)
.notification(function(event){
    alert(event.body);
})