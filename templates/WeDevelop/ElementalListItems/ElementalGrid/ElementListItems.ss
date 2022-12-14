<% if $ShowTitle %>
    <$TitleTag class="$TitleSizeClass">$Title.RAW</$TitleTag>
<% end_if %>
Selection mode: $Mode<br />
Total item count: $Items.Count <%t WeDevelop\ListItems\ElementalGrid\CasePage.PLURALNAME "List items" %>
<% if $Items %>
    <ul class="list">
        <% loop $Items %>
            <li>
                <% if $Icon && $Icon != '' %>
                    <div class="svg-icon">
                        $Icon
                    </div>
                <% end_if %>
                <h2>$Title</h2>
                <div class="content">
                    $Content
                </div>
            </li>
        <% end_loop %>
    </ul>
<% else %>
    <p><%t WeDevelop\ListItems\ElementalGrid\ElementPortfolio.NOITEMSFOUND "No list items found" %></p>
<% end_if %>
