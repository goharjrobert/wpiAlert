param(
    [int]$selectedGroup
)

$temp
$contacts = @()

#if statement for foremen group members
if ($selectedGroup -ge 0 -and $selectedGroup -le 7)
{
    if ($selectedGroup -ge 0 -and $selectedGroup -le 6)
    {
        if ( $selectedGroup -eq 0 )
        {
            $temp = "EUG Foremen"
        }
        elseif ( $selectedGroup -eq 1 )
        {
            $temp = "SPO Foremen"
        }
        elseif ( $selectedGroup -eq 2 )
        {
            $temp = "RENO Foreman"
        }
        elseif ( $selectedGroup -eq 3 )
        {
            $temp = "SEA Foremen"
        }
        elseif ( $selectedGroup -eq 4 )
        {
            $temp = "PDX Foremen"
        }
        elseif ( $selectedGroup -eq 5 )
        {
            $temp = "ABQ Foremen"
        }

        elseif ( $selectedGroup -eq 6 )
        {
            $temp = "LAS Foremen"
        }

        #$foremen = Get-ADGroupMember "EUG Foremen"
        $foremen = Get-ADGroupMember "$temp"
        $contacts += $foremen | Get-ADObject -Properties "GivenName", "mobile" | Where-Object { $_.mobile -ne $null } | Select GivenName, mobile
    }

    elseif ($selectedGroup -eq 7)
    {
        $allForemengroups = (Get-ADGroup -filter { Name -like "Foremen - *" } | Select Name)
        $allForemengroups += (Get-ADGroup -filter { Name -like "*LAS Foremen*" } | Select Name)
        $allForemengroups += (Get-ADGroup -filter { Name -like "*ABQ Foremen*" } | Select Name)

        foreach ($group in $allForemengroups)
        {
            $contacts += Get-ADGroupMember $group.Name | Get-ADObject -Properties "GivenName", "mobile" | Where-Object { $_.mobile -ne $null } | Select GivenName, mobile
        }

    }
}

#if statement for project managers and estimator group members
elseif ($selectedGroup -ge 8 -and $selectedGroup -le 15)
{
    if ( $selectedGroup -eq 8 )
    {
        $temp = "EUG PM-Est"
    }
    elseif ( $selectedGroup -eq 9 )
    {
        $temp = "SPO PM-Est"
    }
    elseif ( $selectedGroup -eq 10 )
    {
        $temp = "RENO PM-Est"
    }
    elseif ( $selectedGroup -eq 11 )
    {
        $temp = "SEA PM-Est"
    }
    elseif ( $selectedGroup -eq 12 )
    {
        $temp = "PDX PM-Est"
    }
    elseif ( $selectedGroup -eq 13 )
    {
        $temp = "ABQ PM-Est"
    }
    elseif ( $selectedGroup -eq 14 )
    {
        $temp = "LAS PM-Est"
    }
    elseif ($selectedGroup -eq 15)
    {
        $temp = "PM-Est"
    }

    #$pm = Get-ADGroupMember "EUG PM-Est";
    $pm = Get-ADGroupMember "$temp"
    $contacts += $pm | Get-ADObject -Properties "GivenName", "mobile" | Where-Object { $_.mobile -ne $null } | Select GivenName, mobile

}

elseif ($selectedGroup -eq 16)
{
    $everyone = Get-ADGroupMember "WPI.Mobile";
    $contacts += $everyone | Get-ADObject -Properties "GivenName", "mobile" | Select GivenName, mobile

    #Get-ADGroupMember "Domain Users" | Get-ADObject -Properties "GivenName", "mobile", ".groups" | Where-Object { $_.mobile -ne $null } | Select GivenName, groups
    #| Where-Object { $_.groups -notmatch 'Disabled Users' }
    #$users = Get-ADGroupMember "Domain Users" | Get-ADUser -Filter * -properties memberof | Where-Object {!($_.memberof -like "Disabled Users" | Where-Object { $_.mobile -ne $null } | Select GivenName
}

elseif ($selectedGroup -eq 17)
{
    $temp = "WPI.IT"
    $it = Get-ADGroupMember "$temp"
    $contacts += $it | Get-ADObject -Properties "GivenName", "mobile" | Where-Object { $_.mobile -ne $null } | Select GivenName, mobile
}

$phoneNumbers = @()
$phoneNumbers += ($contacts | Select mobile).mobile -replace '[ ]',''
$names = @()
$names += ($contacts | Select GivenName).GivenName
$contacts = @()
$contacts = $phoneNumbers + $names

return $contacts