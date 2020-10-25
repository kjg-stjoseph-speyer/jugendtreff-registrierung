<script>
  import {MaterialApp} from 'svelte-materialify';
  import {Alert, Overlay} from 'svelte-materialify/src';
  import TheAppBar from "./components/TheAppBar.svelte";
  import TheFooter from "./components/TheFooter.svelte";
  import EventAccordion from "./components/EventAccordion.svelte";
  import {writeCookie, readCookie} from './CookieHelper';
  import TheNavigationDrawer from "./components/TheNavigationDrawer.svelte";
  import AdminLoginDialog from "./components/AdminLoginDialog.svelte";
  import {onMount} from "svelte";
  import EventEditDialog from "./components/EventEditDialog.svelte";
  import InfoDialog from "./components/InfoDialog.svelte";
  import * as api from './api/EventApi';
  import ErrorDialog from "./components/ErrorDialog.svelte";

  let events = [];

  const theme = "light";

  let showAdminDialog = false;
  let showEventEditDialog = false;
  let showInfoDialog = false;
  let showDrawer = false;
  let expandedEventIndex = 0;
  let currentEventId = -1;

  // error dialog
  let error = {show: false, title: "", message: "", error: null};

  const eventById = eventId => {
    return events.find(e => e.event_id === eventId);
  }

  // called when user requests registration on an event
  const handleRegistration = info => {
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


    // get event by id
    const eventToUpdateIndex = events.findIndex(e => e.event_id === info.eventId);
    const eventToUpdate = events[eventToUpdateIndex];
    const isWaiting = eventToUpdate.registrations.length >= eventToUpdate.max_participants;

    // check for duplicate name
    if (!eventToUpdate.registrations.find(p => p.name === info.name)) {

      const registration = {
        name: info.name, email: userinfo.email, time: info.time.getTime(),
        user_id: userinfo.userid, event_id: info.eventId, waiting: isWaiting
      }

      // TODO: show loading overlay
      api.createRegistration(registration)
        .then(data => {
          eventToUpdate.registrations = [...eventToUpdate.registrations,
            data
          ]
          events.splice(eventToUpdateIndex, 1, eventToUpdate);

          // trigger reactivity
          events = events;
        })
        .catch(e => {
          error = {
            title: "Registrierung Fehlgeschlagen",
            message: "Die Registrierung ist Fehlgeschlagen",
            error: e,
            show: true,
          }
        });


    } else {
      error = {
        title: "Name existiert bereits",
        message: "Es ist bereits jemand mit diesem Namen angemeldet. Wähle einen anderen",
        error: null,
        show: true,
      }
    }

  };

  const handleDeregistration = info => {
    const eventToUpdateIndex = events.findIndex(e => e.event_id === info.eventId);
    const eventToUpdate = events[eventToUpdateIndex];

    // get registration id
    const regId = eventToUpdate.registrations.filter(p => p.user_id === info.userId)[0].registration_id;

    // TODO: show loading overlay
    api.deleteRegistration(regId)
      .then(() => {
        eventToUpdate.registrations = eventToUpdate.registrations.filter(p => p.user_id !== info.userId);
        events.splice(eventToUpdateIndex, 1, eventToUpdate);

        // trigger reactivity
        events = events;
      })
      .catch(e => {
        error = {
          title: "Abmelden fehlgeschlagen",
          message: "Du konntest nicht abgemeldet werden",
          error: e,
          show: true,
        }
      });
  };

  const handleAdminClick = () => {
    if (admin) {
      // delete cookie to log out
      writeCookie("admin", "", 365);
      showDrawer = false;
      admin = false;
    } else {
      // no admin -> show login dialog
      showAdminDialog = true;
      showDrawer = false;
    }
  };

  // admin event handlers
  const handleAdminLogin = password => {
    // TODO: show loading overlay
    api.checkAuthentication(password)
      .then(resp => {
        if (resp.valid === true) {
          writeCookie("admin", password, 365);
          admin = true;
        }else {
          error = {
            title: "Anmeldung Fehlgeschlagen",
            message: "Ungültiges Passwort",
            error: null,
            show: true,
          }
        }
      })
      .catch(e => {
        error = {
          title: "Anmeldung Fehlgeschlagen",
          message: "Die Anmeldung ist fehlgeschlagen",
          error: e,
          show: true,
        }
      })
      .finally(() => {
        showAdminDialog = false;
      });
  };
  const handleDeleteEvent = eventId => {
    const adminKey = readCookie("admin");

    // TODO: show loading overlay
    api.deleteEvent(eventId, adminKey)
      .then(() => {
        // remove locally
        events = events.filter(e => e.event_id !== eventId)
      })
      .catch(e => {
        error = {
          title: "Löschen Fehlgeschlagen",
          message: "Das Löschen des Termins ist fehlgeschlagen",
          error: e,
          show: true,
        }
      });
  };
  const handleEditEvent = update => {
    showEventEditDialog = false;

    const adminKey = readCookie("admin");
    if (update.hasOwnProperty("event_id")) {
      // TODO:show loading overlay
      api.updateEvent(update, adminKey)
        .then(data => {
          // update locally
          const eventIndex = events.findIndex(e => e.event_id === update.event_id);
          events[eventIndex] = data;
          events = events;
        })
        .catch(e => {
          error = {
            title: "Bearbeiten Fehlgeschlagen",
            message: "Das Bearbeiten des Termins ist fehlgeschlagen",
            error: e,
            show: true,
          }
        });
    } else {
      // new event

      // TODO: show loading overlay
      api.createEvent(update, adminKey)
        .then(data => {
          // insert locally
          events = [...events, data];
        })
        .catch(e => {
          error = {
            title: "Erstellen Fehlgeschlagen",
            message: "Das Erstellen des Termins ist fehlgeschlagen",
            error: e,
            show: true,
          }
        });
    }
  };
  const handleAdminDeregister = (eventId, userId) => {
    const eventToUpdateIndex = events.findIndex(e => e.event_id === eventId);
    const eventToUpdate = events[eventToUpdateIndex];

    // get registration id
    const regId = eventToUpdate.registrations.filter(p => p.user_id === userId).registration_id;

    // TODO: show loading overlay
    api.deleteRegistration(regId)
      .then(() => {
        eventToUpdate.registrations = eventToUpdate.registrations.filter(p => p.user_id !== userId);
        events.splice(eventToUpdateIndex, 1, eventToUpdate);

        // trigger reactivity
        events = events;
      })
      .catch(e => {
        error = {
          title: "Löschen Fehlgeschlagen",
          message: "Das Löschen der Registrierung ist fehlgeschlagen",
          error: e,
          show: true,
        }
      });
  };

  const handleUserToggle = (eventId, userId) => {
    // get event
    const eventToUpdateIndex = events.findIndex(e => e.event_id === eventId);
    const eventToUpdate = events[eventToUpdateIndex];

    // get user
    const userIndex = eventToUpdate.registrations.findIndex(p => p.user_id === userId);

    const updatedRegistration = eventToUpdate.registrations[userIndex];
    updatedRegistration.waiting = !updatedRegistration.waiting;

    // TODO: show loading overlay
    api.updateRegistration(updatedRegistration)
      .then(data => {
        eventToUpdate.registrations[userIndex] = data;
        events.splice(eventToUpdateIndex, 1, eventToUpdate);

        // trigger reactivity
        events = events;
      })
      .catch(e => {
        error = {
          title: "Aktualisierung Fehlgeschlagen",
          message: "Die Aktualisierung des Registrierungsstatus ist fehlgeschlagen",
          error: e,
          show: true,
        }
      });
  };

  let admin = false;
  onMount(async () => {
    admin = readCookie("admin") !== "";

    // fetch events using API
    api.fetchAllEvents()
      .then(data => {
        events = data;
      })
      .catch(e => {
        error = {
          title: "Fehler",
          message: "Termine konnten nicht geladen werden",
          error: e,
          show: true,
        }
      });
  });
</script>

<MaterialApp {theme}>
    <TheAppBar admin={admin} on:showdrawer={() => showDrawer = !showDrawer}/>

    <div style="overflow-y: scroll; max-height: 85vh">
        <Alert class="info-alert blue white-text ma-2" border="left" dense>
            <h5>Hinweis</h5>
            Bitte die <a class="white-text text-decoration-underline" on:click={() => showInfoDialog=true}>Regeln</a> durchlesen und beachten!
        </Alert>

        <h4 class="heading mt-8 ml-1 mb-1">Termine</h4>
        <EventAccordion
                on:register={e => handleRegistration(e.detail)}
                on:deregister={e => handleDeregistration(e.detail)}
                on:admindelete={e => handleDeleteEvent(e.detail.eventId)}
                on:adminedit={e => {currentEventId = e.detail.eventId; showEventEditDialog = true}}
                on:adminderegister={e => handleAdminDeregister(e.detail.eventId, e.detail.userId)}
                on:admintoggle={e => handleUserToggle(e.detail.eventId, e.detail.userId)}
                events={events}
                admin={admin}
                expandedIndex={expandedEventIndex}
        />
    </div>
    <TheNavigationDrawer
            on:adminclick={() => handleAdminClick()}
            on:new={() => {currentEventId = -1; showEventEditDialog = true}}
            events={events}
            show={showDrawer}
            admin={admin}
    />
    <Overlay index={1} active={showDrawer} on:click={() => showDrawer = false}/>
    <AdminLoginDialog
            show={showAdminDialog}
            on:cancel={() => showAdminDialog = false}
            on:login={e => handleAdminLogin(e.detail.password)}
    />
    <InfoDialog show={showInfoDialog} on:close={() => showInfoDialog = false}/>
    <ErrorDialog
            title={error.title}
            text={error.message}
            error={error.error}
            show={error.show} on:close={() => error.show = false}
    />
    {#if admin}
        <EventEditDialog
            show={showEventEditDialog}
            event={eventById(currentEventId)}
            on:cancel={() => showEventEditDialog = false}
            on:save={e => handleEditEvent(e.detail)}
        />
    {/if}
    <TheFooter />
</MaterialApp>


<style>
    .link{
        text-underline: white;
    }
    .info-alert {
        margin: 15px;
    }
</style>
