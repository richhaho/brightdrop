# Front End Documentation

---

- [Setup and Installation](#section-1)
- [Reports Page](#section-2)
- [Contents Page](#section-3)
- [Contents Engagements Page](#section-3.1)
- [Leads Page](#section-4)
- [Leads ID Page](#section-4.1)

<a name="section-1"></a>
## Overview

Cool New Documentations Page.. 🦊




<a name="section-2"></a>
## Reports Page


### Total Conversions

> `App`:

![image](/img/Docs/t1.png)

> `Db`: totalconvertreporting_u

``` js
> Total Conversions shows the human traffic on the site for each day.
The Bar graph shows the number of unique conversions (Unique `u_id`) by date
Total conversions is the sum of all the conversions in the date range selected
```


> {primary} Note: When taking in the sum of conversions in a date range its the frequency of conversions rather than just unique conversions.

###  Conversion by site

> `App`:

![image](/img/Docs/c1.png)

> `Db`: conversionbysite_u


``` js
> Conversion by site shows the conversion traffic across client's sites ( if multiple )
The pie chart shows the number of unique conversions by Site (URL)
The modal box below shows the percentage representation of the same.

```

### Visitor breakdown

> `App`:

![image](/img/Docs/v1.png)


> `Db`: VisitorBreakdown_u

``` js
> Visitors breakdown shows the collated information about users and related site statistics.

Total Unique ID's: Total number of unique e_id.

Unique ID's Blocking Cookies: Total number of e_id's blocking cookies ie.., e_id's with `allow_cookies` not `true or 1`.

Unique ID's Deleting Cookies: Total number of e_id's deleting cookies ie.., e_id's whose `has_cookies` column has more than one
`fal/new`

Total unique conversions: Total number of `unique emails`.

Total content activities: Total number of `URL's` .

Unique Content Pieces: Total number of `Unique URL's`.

Returning users after 60 mins and 30 mins: Total number of `e_id` that returned after respective timings.

Average pages: calculated by dividing `Total Content Activities/ Total
Unique users`.
```

### Account Based Marketing

> `App`:

![image](/img/Docs/a1.png)

> `Db`: ABMaggregate

```js
> Account Based Marketing shows information specific to only companies wiz.., the e_id's who have converted with a company mail address.

Companies Visiting: Total Number of  `unique company names`.

Content Consumed: The total number of URL's visited by the filtered `e_id`.

Conversions: Total number of the `unique emails` obtained from filtered `e_id`.

```

### Account Based Marketing companies


> `App`:
![image](/img/Docs/ab1.png)

> `Db`: ABM_CV_Details_u

```js
> ABM companies shows the conversions breakdown by each company.

Content Activities: The number of the contents('URLs') generated for each domain.

Conversion Frequency: The total number of conversions for each domain.

Unique Conversions: The number of unique conversions for each domain. (`unique
email` )

```

### Channel Overview
> Channel Overview is a simplified representation of user activity based on channels.
Channels are divided into Search Engine, Social Media, Organic, Affiliate, PPC and
Email.

If any URL has N/A they are considered organic, and if any URL qualifies
in the lists from below they are categorized respectively. Anything
outside of these sorting criteria is categorized as Affiliate.

### Lists for sorting based on channels

| # | Search Engine | Social | PPC |
|---|---|---|---|
| 1 | Google | Facebook | `src=AW` |
| # | Bing | T | `src=KW` |
| # | Ask | Instagram | `src=adwords` |
| # | Baidu | Tumblr |  |
| # | Aol | Whatsapp |  |
| # | Wolfram Aplha| WeChat |  |
| # | Yahoo | Pinterest |  |
| # |  | Linkedin |  |

### Unique Id's


> `App`:
![image](/img/Docs/u1.png)


> `Db`: uniqueidreporting_u
```js
> Unique IDs shows the channel used by unique e_id's during their first visit to the clients site.
Its calculated by obtaining `unique e_id` by filtering the `e_id` by id and picking their first point of entry. The e_id's are then classified accordingly using our sorting algo.
```

### Content Activity


> `App`:
![image](/img/Docs/ca1.png)



> `Db`:contentactivitiesreporting_u
``` js
> Content Activity shows the channel used by during every visit of the e_id's
Its calculated by obtaining all the content activities. and then classifying accordingly using our sorting algo.
```
### Conversion


> `App`:
![image](/img/Docs/ct1.png)


> `Db`: conversionreporting_u

``` js
> Content Activity shows the channel used by e_id during the conversion visit.
For all the converted e_id, obtain the first point of entry for a converted session and then expose it to the sorting algo..
```

### Conversion forms


> `App`:
![image](/img/Docs/cf1.png)


> `Db`: conversionforms_u

```js
> Statistics of the converted forms ie.., site of conversion of e_ids.

Conversions: The total number of conversions on a page URL and title. (`email`)

Conversions %: The value acquired by dividing  `conversions for a page URL / total conversion for a
client`.

```

### Content Consumed

> `App`:
![image](/img/Docs/cc1.png)


> `Db`:

```js
> The content consumed by the converted e_id's before conversion.

Value: The number of occurences of any given PageURL and PageName.

Average time: The mean/average of time spent over all the occurences for a specific URL and PageName.
```

### Path To Conversion

> `App`: 
![image](/img/Docs/up.png)


> `Db`: up

```js
> Shows the journey taken by a user for conversion.
Fetches the entries from db using client_id and e_id.
Displays a tree which starts with channel, shows the conversion path and ends with converted path url.
```



<a name="section-3"></a>
## Contents Page


### Best Performing Content

> `App`:
![image](/img/Docs/bpc.png)


> `Db`: Contents

```js
> Shows the Best Performing Content in regards to engagement.

Score: Score is calculated by comparing the conversions of each url against all the conversions and then each url is scored against a high of 10.
Engagement: Total number of occurences of a url
Stage: Refers to a stage of the process a url is in,
```

### Least Performing Content

> `App`:
![image](/img/Docs/lpc.png)


> `Db`: Contents

```js
> Shows the Least Performing Content in regards to engagement.
Score: Score is calculated by comparing the conversions of each url against all the conversions and then each url is scored against a high of 10.
Engagement: Total number of occurences of a url
Stage: Refers to a stage of the process a url is in,

```

### Contents Trending

> `App`:
![image](/img/Docs/ct.png)


> `Db`: Contents

```js
> Shows the latest contents with respect to date of entry.
Score: Score is calculated by comparing the conversions of each url against all the conversions and then each url is scored against a high of 10.
Engagement: Total number of occurences of a url
Stage: Refers to a stage of the process a url is in
Last Seen: Last seen refers to the date the url was last seen in the entries.

```

### Top Performing Content

> `App`:
![image](/img/Docs/tpc.png)


> `Db`: Contents

```js
> Shows the statistics and Page information of the contents.
Fetches entries from contents table sorted in decresing order of engagements
Engagements Generated: Total number of occurences of a url
Last Seen: Last seen refers to the date the url was last seen in the entries.
```

### Top Keywords Used

> `App`:
![image](/img/Docs/tku.png)


> `Db`: Contents

```js
> Shows an abstract cloud of keywords from the top contents consumed.
Fetches entries from contents table sorted in decresing order of engagements and genereates a word cloud .
```

<a name="section-3.1"></a>
## Contents Engagement

### Performance

> `App`:
![image](/img/Docs/perf.png)


> `Db`: topentry_u

```js
> Shows the number of engaged users by each day.
Fetches entries from the db table using params: PageName, PageURL and Date.
```

### Engagement

> `App`:
![image](/img/Docs/enga.png)


> `Db`: Contents

```js
> Shows the statistics of users in each of the stages with respect to a particular content.
Fetches entries from the db table using params: PageName and PageURL.
The days functionality doesn't work since the db has an aggregate data available.
```

### Top Entry Points

> `App`:
![image](/img/Docs/tep.png)


> `Db`: topentry_u

```js
> Shows the top entry channels used by the users when visiting a piece of content.
Fetches entries from the db table using params: PageName, PageURL and Date.
```


### Visitor Engagement
> `App`:
![image](/img/Docs/hm.png)


> `Db`: clickmap

```js
> Visitor Engagement tab is responsible for showing user behaviour patterns
Fetches 500 entries from the db table using params: PageName and PageURL.

```

<a name="section-4"></a>

## Leads Page

Database Tables : --,--,--

### Lead Funnel

> `App`:
![image](/img/Docs/lf.png)


> `Db`: VisitorBreakdown_u

```js
> Shows a simplified information about user activity. 

Total Users: The number of unique e_ids.
Engaged Users: The number of unique e_ids returning after 60 mins.
Converted Users: The number of unique emails.
```

### Lead Progression

> `App`:
![image](/img/Docs/lp.png)


> `Db`: lead_progression

```js
> Shows a percentage breakdown of user's stages
Fetches the entries from db using client_id.
```
### Recent Leads

> `App`:
![image](/img/Docs/rl.png)


> `Db`: Lead_ids

```js
> Shows the top 100 content entries of converted users
Fetches the entries from db using client_id.

User ID: e_id from the table.
Channel: Each e_ids first visit i.e.., PageURL is exposed through sorting algo. and lands in one of the channels.
Latest Content: Latest Page Name of the e_id.
Stage: Currently the table only consists of converted e_ids.
Last Seen: Last visit date of e_ids.


```

<a name="section-4.1"></a>

## Leads ID Page

### User ID

> `App`:
![image](/img/Docs/lui.png)


> `Db`: Lead_ids

```js
> Shows the detailed information about users.
Fetches the entries from db using client_id.

User ID: e_id from the table.
Channel: Each e_ids first visit i.e.., PageURL is exposed through sorting algo. and lands in one of the channels.
Last Seen: Last visit date of e_ids.
Device: The device the e_id uses (ex. laptop or desktop)
Browser: The browser the e_id uses (ex. Firefox)
Blocks Cookie: Whether the e_id blocks cookies
Delete Cookie: Whether the e_id deletes cookies

```

### Lead Progression

> `App`:
![image](/img/Docs/llp.png)


> `Db`: lead_progression

```js
> Shows the users content activities in regards to stages.

Stage: The stage a specific content belongs to. 
Last seen: Last entry point of a specific content.
 ```

### Path to Conversion
> `App`:
![image](/img/Docs/ptcl.png)


> `Db`: up

```js
> Shows the journey taken by a user for conversion.
Fetches the entries from db using client_id and e_id.
Displays a tree which starts with channel, shows the conversion path and ends with converted path url.
```
