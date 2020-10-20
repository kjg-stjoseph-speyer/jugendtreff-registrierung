<script>
  import { createEventDispatcher } from 'svelte'
  import { Card, CardTitle, CardActions, CardText, Dialog, Button } from 'svelte-materialify/src';

  const dispatch = createEventDispatcher();

  export let title = "Bestätigen";
  export let text = "";
  export let show = false;
</script>

<Dialog persistent bind:active={show}>
    <Card>
        <CardTitle>{title}</CardTitle>
        <CardText>
            {text}
        </CardText>
        <CardActions>
            <Button on:click={() => dispatch("cancel")} text>Abbrechen</Button>
            <Button on:click={() => dispatch("confirm")} text>Bestätigen</Button>
        </CardActions>
    </Card>
</Dialog>
