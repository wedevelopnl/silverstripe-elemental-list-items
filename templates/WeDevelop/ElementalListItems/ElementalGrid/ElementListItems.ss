<% if $ShowTitle %>
    <$TitleTag class="$TitleSizeClass">$Title.RAW</$TitleTag>
<% end_if %>
<hr />
$Items.Count <%t WeDevelop\ListItems\ElementalGrid\CasePage.PLURALNAME "List items" %>
<hr /><br />
<% if $Items %>
    <ul>
        <% loop $Items %>
            <li>
                <% if $Icon && $Icon != '' %>
                    <div class="svg-icon">
                        $Icon
                    </div>
                <% end_if %>
                $Title
                $Content
            </li>
        <% end_loop %>
    </ul>
<% else %>
    <p><%t WeDevelop\ListItems\ElementalGrid\ElementPortfolio.NOITEMSFOUND "No list items found" %></p>
<% end_if %>
