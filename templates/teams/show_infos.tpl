{if count($errors) || count($ok) || count($alerts) }
          <ul id="infos">
{foreach from=$errors item=error}
          <li class="error">{$error}</li>
{/foreach}
{foreach from=$ok item=iok}
          <li class="ok">{$iok}</li>
{/foreach}
{foreach from=$alerts item=alert}
          <li class="alert">{$alert}</li>
{/foreach}
          </ul>
{/if}