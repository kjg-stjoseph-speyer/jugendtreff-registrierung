<script>
  import { createEventDispatcher } from 'svelte'
  import { Card, CardTitle, CardActions, CardText, Dialog, Button, Icon } from 'svelte-materialify/src';
  import ExpansionPanels, {ExpansionPanel} from 'svelte-materialify/src/components/ExpansionPanels';
  import {mdiAlertCircle} from '@mdi/js';

  const dispatch = createEventDispatcher();

  export let title = "Fehler";
  export let text = "";
  export let show = false;
  export let error = null;
</script>

<Dialog persistent bind:active={show}>
    <Card>
        <CardTitle><Icon class="red-text mr-3" path={mdiAlertCircle} /> {title}</CardTitle>
        <CardText>
            {text} <br>

            <ExpansionPanels class="mt-5" accordion flat>
                <ExpansionPanel>
                    <span slot="header">
                        Details
                    </span>

                    {error !== null ? JSON.stringify(error, null, 2) : ""}
                </ExpansionPanel>
            </ExpansionPanels>
        </CardText>
        <CardActions>
            <Button on:click={() => dispatch("close")} text>Schließen</Button>
        </CardActions>
    </Card>
</Dialog>
