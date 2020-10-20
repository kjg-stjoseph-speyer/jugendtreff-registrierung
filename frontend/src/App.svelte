<script>
  import {MaterialApp} from 'svelte-materialify';
  import {Alert, Overlay} from 'svelte-materialify/src';
  import TheAppBar from "./components/TheAppBar.svelte";
  import TheFooter from "./components/TheFooter.svelte";
  import EventAccordion from "./components/EventAccordion.svelte";
  import {writeCookie, readCookie} from './CookieHelper';
  import {testEvents} from './dummyData';
  import TheNavigationDrawer from "./components/TheNavigationDrawer.svelte";
  import AdminLoginDialog from "./components/AdminLoginDialog.svelte";
  import {afterUpdate} from "svelte";

  let events = testEvents;

  const theme = "light";

  let showAdminDialog = false;
  let showDrawer = false;
  let expandedEventIndex = 0;

  // called when user requests registration on an event
  const handleRegistration = info => {
    console.log(info);

    // create cookie with user information
    const saved = readCookie("userinfo");

    // check if value already exists
    let userinfo;

    if (saved === "") {
      // create cookie
      const userid = Math.floor(Math.random() * (99999999 - 10000000 + 1)) + 10000000;
      userinfo = {name: info.name, email: info.email, userid: userid};
    } else {
      // update cookie
      userinfo = JSON.parse(saved);
      userinfo.name = info.name;
      userinfo.email = info.email;
    }
    writeCookie("userinfo", JSON.stringify(userinfo), 365);


    // TODO: register using API
    // for now just add the changes locally

    // get event by id
    const eventToUpdateIndex = events.findIndex(e => e.id === info.eventId);
    const eventToUpdate = events[eventToUpdateIndex];
    const isWaiting = eventToUpdate.participants.length >= eventToUpdate.maxParticipants;

    // check for duplicate name
    if (!eventToUpdate.participants.find(p => p.name === info.name)) {
      eventToUpdate.participants = [...eventToUpdate.participants,
        {name: info.name, timePreference: info.time.getTime(), userid: userinfo.userid, waiting: isWaiting}
      ]
      events.splice(eventToUpdateIndex, 1, eventToUpdate);

      // trigger reactivity
      events = events;
    } else {
      console.log("Duplicate")
    }

  };

  const handleDeregistration = info => {
    console.log(info);

    // TODO: remove registration using API

    // for now just remove changes locally
    const eventToUpdateIndex = events.findIndex(e => e.id === info.eventId);
    const eventToUpdate = events[eventToUpdateIndex];
    eventToUpdate.participants = eventToUpdate.participants.filter(p => p.userid !== info.userId);
    events.splice(eventToUpdateIndex, 1, eventToUpdate);

    // trigger reactivity
    events = events;
  };

  const handleAdminClick = () => {
    if (admin) {
      // delete cookie to log out
      writeCookie("admin", "", 365)
    }else {
      // no admin -> show login dialog
      showAdminDialog = true;
    }
    showDrawer = false;
  };

  // admin event handlers
  const handleAdminLogin = password => {
    // TODO: check credentials using API

    if (password === "123456") {
      writeCookie("admin", password, 365);
    }

    showAdminDialog = false;
  };
  const handleDeleteEvent = eventId => {
    // TODO: remove event using API

    // remove locally
    events = events.filter(e => e.id !== eventId)
  };
  const handleEditEvent = eventId => {
    // TODO: show edit dialog
  };
  const handleAdminDeregister = (eventId, userId) => {
    // TODO: remove registration using API

    // remove locally
    // for now just remove changes locally
    const eventToUpdateIndex = events.findIndex(e => e.id === eventId);
    const eventToUpdate = events[eventToUpdateIndex];
    eventToUpdate.participants = eventToUpdate.participants.filter(p => p.userid !== userId);
    events.splice(eventToUpdateIndex, 1, eventToUpdate);

    // trigger reactivity
    events = events;
  };
  const handleUserToggle = (eventId, userId) => {
    console.log("Toggle user " + userId + " on event " + eventId);
    // TODO: toggle using API

    // for now just update changes locally

    // get event
    const eventToUpdateIndex = events.findIndex(e => e.id === eventId);
    const eventToUpdate = events[eventToUpdateIndex];

    // get user
    const userIndex = eventToUpdate.participants.findIndex(p => p.userid === userId);
    // toggle waiting status
    eventToUpdate.participants[userIndex].waiting = !eventToUpdate.participants[userIndex].waiting;

    events.splice(eventToUpdateIndex, 1, eventToUpdate);

    // trigger reactivity
    events = events;
  };

  let admin = false;
  afterUpdate(async () => {
    admin = readCookie("admin") !== "";
  });
</script>

<MaterialApp {theme}>
    <TheAppBar on:showdrawer={() => showDrawer = !showDrawer}/>
    <Alert class="info-alert blue white-text ma-2" border="left" dense>
        <h5>Hinweis</h5>
        Bitte Hygienehinweise beachten!
    </Alert>

    <h4 class="heading mt-8 ml-1 mb-1">Termine</h4>
    <EventAccordion
            on:register={e => handleRegistration(e.detail)}
            on:deregister={e => handleDeregistration(e.detail)}
            on:admindelete={e => handleDeleteEvent(e.detail.eventId)}
            on:adminedit={e => handleEditEvent(e.detail.eventId)}
            on:adminderegister={e => handleAdminDeregister(e.detail.eventId, e.detail.userId)}
            on:admintoggle={e => handleUserToggle(e.detail.eventId, e.detail.userId)}
            events={events}
            expandedIndex={expandedEventIndex}
    />
    <TheNavigationDrawer
            on:eventclick={e => {
              expandedEventIndex = events.findIndex(ev => ev.id === e.detail.eventId);
              showDrawer = false;
            }}
            on:adminclick={() => handleAdminClick()}
            events={events}
            show={showDrawer}
    />
    <Overlay index={1} active={showDrawer} on:click={() => showDrawer = false}/>
    <AdminLoginDialog
            show={showAdminDialog}
            on:cancel={() => showAdminDialog = false}
            on:login={e => handleAdminLogin(e.detail.password)}
    />
    <TheFooter />
</MaterialApp>


<style>
    .info-alert {
        margin: 15px;
    }
</style>
