@section('css')


<style type="text/css">
 @media print {
    #page {

        font-size: 13pt !important;

    }
    
    .small {
    font-size: 10pt !important;
  
    }
    .bold {
        font-size: 13pt !important;
    font-weight: bold;
    }
}

@media screen {
    #page {

        font-size: 10pt !important;

    }
    
    .small {
    font-size: 7pt !important;
  
    }
    .bold {
        font-size: 11pt !important;
    font-weight: bold;
    }
}
</style>

@append

<div id="page">
    <div class="content">
        <center><h1>Reports</h1></center>
        <br>

    <h2 class="text-left">Conversion Overview – </h2><br>
         <?php for ($i=0;$i<5;$i++){ ?>
         Total Conversions for {{$days[$i]}} days: {{$conversionOverview[$days[$i]]}}<br> 
         <?php }?>

         Total Conversions for Overall days: {{$conversionOverview[$days[5]]}}<br> 
 
    <h2 class="text-left">Visitor Breakdown – </h2><br>
        <?php for ($i=0;$i<5;$i++){ ?>
            Total Unique Id’s for {{$days[$i]}} days: {{$visitors_breakdown['TotalUniqueIDs'][$days[$i]]}}<br>
            Unique ID’s Blocking Cookies for {{$days[$i]}} days: {{$visitors_breakdown['UniqueIDsBlockingCookies'][$days[$i]]}}<br>
            Unique ID’s Deleting Cookies for {{$days[$i]}} days: {{$visitors_breakdown['DeleteCookies'][$days[$i]]}}<br>
            Total Unique Conversions for {{$days[$i]}} days: {{$visitors_breakdown['TotalUniqueConversions'][$days[$i]]}}<br>
            Total Content Activities for {{$days[$i]}} days: {{$visitors_breakdown['TotalContentActivities'][$days[$i]]}}<br>
            Unique Content Pieces for {{$days[$i]}} days: {{$visitors_breakdown['UniqueContentActivities'][$days[$i]]}}<br>
            Returning Users 60 Mins for {{$days[$i]}} days: {{$visitors_breakdown['Return60'][$days[$i]]}}<br>
            Returning Users 30 Mins for {{$days[$i]}} days: {{$visitors_breakdown['Return30'][$days[$i]]}}<br>
            Average Pages Consumed for {{$days[$i]}} days: {{$visitors_breakdown['AveragePagesConsumed'][$days[$i]]}}<br>
            <br>
        <?php }?>
            Total Unique Id’s for Overall days: {{$visitors_breakdown['TotalUniqueIDs'][$days[5]]}}<br>
            Unique ID’s Blocking Cookies for Overall days: {{$visitors_breakdown['UniqueIDsBlockingCookies'][$days[5]]}}<br>
            Unique ID’s Deleting Cookies for Overall days: 5{{$visitors_breakdown['DeleteCookies'][$days[5]]}}<br>
            Total Unique Conversions for Overall days: {{$visitors_breakdown['TotalUniqueConversions'][$days[5]]}}<br>
            Total Content Activities for Overall days: {{$visitors_breakdown['TotalContentActivities'][$days[5]]}}<br>
            Unique Content Pieces for Overall ays: {{$visitors_breakdown['UniqueContentActivities'][$days[5]]}}<br>
            Returning Users 60 Mins for Overall days: {{$visitors_breakdown['Return60'][$days[5]]}}<br>
            Returning Users 30 Mins for Overall days: {{$visitors_breakdown['Return30'][$days[5]]}}<br>
            Average Pages Consumed for Overall days: {{$visitors_breakdown['AveragePagesConsumed'][$days[5]]}}<br>
            <br>


       
     
    <h2 class="text-left">Account Based Marketing –</h2> <br>
        Companies Visiting: {{$ABM_detail->TotalCompaniesVisiting}}<br>
        Content Consumed: {{$ABM_detail->TotalContents}}<br>
        Conversions: {{$ABM_detail->Conversions}}<br>
 

    <h2 class="text-left">Channel Overview – </h2><br>
        <h3 class="text-left">Unique Id’s – </h3><br>
        <?php for ($i=0;$i<5;$i++){ ?>    
            Search Engines for {{$days[$i]}} days: @if ($UniqueIds['SE'][$days[$i]]) {{$UniqueIds['SE'][$days[$i]]->count}} @endif<br>
            Organic for {{$days[$i]}} days: @if($UniqueIds['O'][$days[$i]]) {{$UniqueIds['O'][$days[$i]]->count}} @endif<br>
            Social Media for {{$days[$i]}} days: @if ($UniqueIds['S'][$days[$i]]) {{$UniqueIds['S'][$days[$i]]->count}} @endif<br>
            Affiliate for {{$days[$i]}} days: @if($UniqueIds['A'][$days[$i]]) {{$UniqueIds['A'][$days[$i]]->count}} @endif<br>
            PPC for {{$days[$i]}} days: @if($UniqueIds['PPC'][$days[$i]]) {{$UniqueIds['PPC'][$days[$i]]->count}} @endif<br> 
            Email for {{$days[$i]}} days: @if ($UniqueIds['EM'][$days[$i]]) {{$UniqueIds['EM'][$days[$i]]->count}} @endif<br>
             <br>
        <?php }?>
            Search Engines for Overall days: @if ($UniqueIds['SE'][$days[5]]) {{$UniqueIds['SE'][$days[5]]->count}} @endif<br>
            Organic for Overall days: @if($UniqueIds['O'][$days[5]]) {{$UniqueIds['O'][$days[5]]->count}} @endif<br>
            Social Media for Overall days: @if ($UniqueIds['S'][$days[5]]) {{$UniqueIds['S'][$days[5]]->count}} @endif<br>
            Affiliate for Overall days: @if($UniqueIds['A'][$days[5]]) {{$UniqueIds['A'][$days[5]]->count}} @endif<br>
            PPC for Overall days: @if($UniqueIds['PPC'][$days[5]]) {{$UniqueIds['PPC'][$days[5]]->count}} @endif<br> 
            Email for Overall days: @if ($UniqueIds['EM'][$days[5]]) {{$UniqueIds['EM'][$days[5]]->count}} @endif<br>
             <br>




        <h3 class="text-left">Content Activity – </h3><br>
            <?php for ($i=0;$i<5;$i++){ ?>    
            Search Engines for {{$days[$i]}} days: @if ($ContentActivitie['SE'][$days[$i]]) {{$ContentActivitie['SE'][$days[$i]]->count}} @endif<br>
            Organic for {{$days[$i]}} days: @if($ContentActivitie['O'][$days[$i]]) {{$ContentActivitie['O'][$days[$i]]->count}} @endif<br>
            Social Media for {{$days[$i]}} days: @if ($ContentActivitie['S'][$days[$i]]) {{$ContentActivitie['S'][$days[$i]]->count}} @endif<br>
            Affiliate for {{$days[$i]}} days: @if($ContentActivitie['A'][$days[$i]]) {{$ContentActivitie['A'][$days[$i]]->count}} @endif<br>
            PPC for {{$days[$i]}} days: @if($ContentActivitie['PPC'][$days[$i]]) {{$ContentActivitie['PPC'][$days[$i]]->count}} @endif<br> 
            Email for {{$days[$i]}} days: @if ($ContentActivitie['EM'][$days[$i]]) {{$ContentActivitie['EM'][$days[$i]]->count}} @endif<br>
             <br>
        <?php }?>
            Search Engines for Overall days: @if ($ContentActivitie['SE'][$days[5]]) {{$ContentActivitie['SE'][$days[5]]->count}} @endif<br>
            Organic for Overall days: @if($ContentActivitie['O'][$days[5]]) {{$ContentActivitie['O'][$days[5]]->count}} @endif<br>
            Social Media for Overall days: @if ($ContentActivitie['S'][$days[5]]) {{$ContentActivitie['S'][$days[5]]->count}} @endif<br>
            Affiliate for Overall days: @if($ContentActivitie['A'][$days[5]]) {{$ContentActivitie['A'][$days[5]]->count}} @endif<br>
            PPC for Overall days: @if($ContentActivitie['PPC'][$days[5]]) {{$ContentActivitie['PPC'][$days[5]]->count}} @endif<br> 
            Email for Overall days: @if ($ContentActivitie['EM'][$days[5]]) {{$ContentActivitie['EM'][$days[5]]->count}} @endif<br>
             <br>
        




        <h3 class="text-left">Conversion – </h3><br>
            <?php for ($i=0;$i<5;$i++){ ?>    
            Search Engines for {{$days[$i]}} days: @if ($Conversion['SE'][$days[$i]]) {{$Conversion['SE'][$days[$i]]->count}} @endif<br>
            Organic for {{$days[$i]}} days: @if($Conversion['O'][$days[$i]]) {{$Conversion['O'][$days[$i]]->count}} @endif<br>
            Social Media for {{$days[$i]}} days: @if ($Conversion['S'][$days[$i]]) {{$Conversion['S'][$days[$i]]->count}} @endif<br>
            Affiliate for {{$days[$i]}} days: @if($Conversion['A'][$days[$i]]) {{$Conversion['A'][$days[$i]]->count}} @endif<br>
            PPC for {{$days[$i]}} days: @if($Conversion['PPC'][$days[$i]]) {{$Conversion['PPC'][$days[$i]]->count}} @endif<br> 
            Email for {{$days[$i]}} days: @if ($Conversion['EM'][$days[$i]]) {{$Conversion['EM'][$days[$i]]->count}} @endif<br>
             <br>
        <?php }?>
            Search Engines for Overall days: @if ($Conversion['SE'][$days[5]]) {{$Conversion['SE'][$days[5]]->count}} @endif<br>
            Organic for Overall days: @if($Conversion['O'][$days[5]]) {{$Conversion['O'][$days[5]]->count}} @endif<br>
            Social Media for Overall days: @if ($Conversion['S'][$days[5]]) {{$Conversion['S'][$days[5]]->count}} @endif<br>
            Affiliate for Overall days: @if($Conversion['A'][$days[5]]) {{$Conversion['A'][$days[5]]->count}} @endif<br>
            PPC for Overall days: @if($Conversion['PPC'][$days[5]]) {{$Conversion['PPC'][$days[5]]->count}} @endif<br> 
            Email for Overall days: @if ($Conversion['EM'][$days[5]]) {{$Conversion['EM'][$days[5]]->count}} @endif<br>
             <br>
     
    </div>
</div>