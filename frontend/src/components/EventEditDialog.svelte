<script>
  import {Button, Dialog, Card, CardTitle, CardText, CardActions, TextField, Icon} from 'svelte-materialify/src';
  import format from "date-fns/format";
  import {de} from "date-fns/locale";
  import {mdiCalendar} from "@mdi/js";
  import Flatpickr from "svelte-flatpickr";
  import {createEventDispatcher} from "svelte";

  const dispatch = createEventDispatcher();

  // format a unix timestamp to date/time string
  const formatDateTime = timestamp => {
    return format(new Date(timestamp), 'EEEE d. MMM. HH:mm', {locale: de}) + " Uhr";
  };

  let options = {
    enableTime: true,
    time_24hr: true,
    dateFormat: "D, d. M. H:i",
    wrap: true,
    onChange: (selectedDates, dateStr, instance) => {
      editedEvent.time = selectedDates[0].getTime()
    }
  };

  let editedEvent = {maxParticipants: 15};

  $: {
    if (event !== null && event !== undefined) {
      editedEvent = event;
    }else {
      event = {time: new Date().getTime(), maxParticipants: 15, inCharge: ""}
    }
  }

  export let show = false;
  export let event = null;
</script>

<Dialog fullscreen bind:active={show}>
    <Card>
        <CardTitle>
            <h5>{event === null || event === undefined ? "Neuer Termin" : formatDateTime(event.time)}</h5>
        </CardTitle>
        <CardText>
            <Flatpickr
                    {options}
                    element="#material-picker"
            >
                <div class="flatpickr" id="material-picker">
                    <TextField
                            class="mt-3"
                            placeholder={event !== null && event !== undefined ? formatDateTime(event.time) : ""}
                            data-input
                    >
                        Termin
                        <div slot="append">
                            <a title="Termin auswählen" data-toggle>
                                <Icon path={mdiCalendar}/>
                            </a>
                        </div>
                    </TextField>
                </div>
            </Flatpickr>

            <TextField
                type=number
                min={1}
                bind:value={event.maxParticipants}
            >
                Max. Teilnehmer
            </TextField>

            <TextField
                bind:value={event.inCharge}
            >
                Verantwortlicher
            </TextField>
        </CardText>
        <CardActions class="justify-end">
            <Button on:click={() => dispatch("cancel")} text>Abbrechen</Button>
            <Button on:click={() => {editedEvent.maxParticipants = parseInt(editedEvent.maxParticipants) ;dispatch("save", editedEvent)}} text>Speichern</Button>
        </CardActions>
    </Card>
</Dialog>
