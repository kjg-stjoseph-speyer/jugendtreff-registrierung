<script>
  import {Button, Dialog, Card, CardTitle, CardText, CardActions, TextField, Icon} from 'svelte-materialify/src';
  import { createEventDispatcher } from 'svelte'
  import {mdiEye, mdiEyeOff} from '@mdi/js';

  const dispatch = createEventDispatcher();

  let showPassword = false;
  let password;
  export let show = false;
</script>

<Dialog persistent bind:active={show}>
    <Card>
        <CardTitle>Admin Anmeldung</CardTitle>
        <CardText>
            Gib daś Administrator Passwort ein

            <TextField class="mt-3" type={showPassword ? 'text' : 'password'} bind:value={password}>
                Passwort
                <div slot="append" on:click={() => showPassword = !showPassword}>
                    <Icon path={showPassword ? mdiEyeOff : mdiEye}/>
                </div>
            </TextField>
        </CardText>
        <CardActions>
            <Button on:click={() => dispatch("cancel")} text>Abbrechen</Button>
            <Button on:click={() => dispatch("login", {password: password})} text>Anmelden</Button>
        </CardActions>
    </Card>
</Dialog>

