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
Number of unique conversions (Unique `u_id`) by date and total conversions is the sum of conversions by date selected 
```


> {primary} Note: while its basically shows the total unique conversions by date, when taking a sum of a group its the frequency of conversions.

###  Conversion by site

> `App`:

![image](/img/Docs/c1.png)

> `Db`: conversionbysite_u


``` js 
Number of Unique Conversions (Unique `email`) by site (URL)

```

### Visitor breakdown

> `App`: 

![image](/img/Docs/v1.png)


> `Db`: VisitorBreakdown_u

``` js
Total Unique ID's: Total number of unique e_id.

Unique ID's Blocking Cookies:  e_id's with `allow_cookies` not `true or 1`.

Unique ID's Deleting Cookies:  e_id's whose `has_cookies` column has more than one
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
> ABM activities contains filtered domains of the companies.i.e.., each  `e_id` with conversion is associated with name of the company.

Companies Visiting: Total Number of  `unique company names`.

Content Consumed: The total number of URL's visited by the filtered `e_id`.

Conversions: Total number of the `unique emails` obtained from filtered `e_id`.

```

### ABM companies 


> `App`:
![image](/img/Docs/ab1.png)

> `Db`: ABM_CV_Details_u

```js 
ABM companies shows the conversions breakdown by each company.

Content Activities: The number of the contents('URLs') generated for each domain.

Conversion Frequency: The total number of conversions for each domain.

Unique Conversions: The number of unique conversions for each domain. (`unique
email` )

```

### Channel Overview 

We check for Search Engine, Social Media, Organic, Affiliate, PPC and
Email in the `page URL` and `prev site`.

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
Obtain `unique e_id` by filtering the `e_id` by id and keeping their first point of entry and then exposing it to our sorting algo.
```

### Content Activity


> `App`:
![image](/img/Docs/ca1.png)



> `Db`:contentactivitiesreporting_u
``` js 
Obtain all the content activities and then expose it to our sorting algo.
```
### Conversion


> `App`:
![image](/img/Docs/ct1.png)


> `Db`: conversionreporting_u

``` js 
For all the converted e_id, obtain the first point of entry for a session and then expose it to the sorting algo..
```

### Conversion forms


> `App`:
![image](/img/Docs/cf1.png)


> `Db`: conversionforms_u

```js
Conversions: The total number of conversions on a page URL and title. (`email`)

Conversions %: The value acquired by dividing  `conversions for a page URL / total conversion for a
client`.

```

### Content Consumed

> `App`:
![image](/img/Docs/cc1.png)


> `Db`:

```js
Value: The number of occurences of any given PageURL and PageName.

Average time: The mean/average of time spent over all the occurences for a specific URL and PageName.
```

<a name="section-3"></a>
## Contents Page


### Best Performing Content

> `App`:
![image](/img/Docs/)


> `Db`: Contents

```js
Fetches the top best performing content in terms of engagement from the Contents table.

Score: Score is calculated by comparing the conversions of each url against all the conversions and then each url is scored against a high of 10.
Engagement: Total number of occurences of a url
Stage: Refers to a stage of the process a url is in,
```

### Least Performing Content

> `App`:
![image](/img/Docs/)


> `Db`: Contents

```js
Fetches the least performing content in terms of engagement from the Contents table.
Score: Score is calculated by comparing the conversions of each url against all the conversions and then each url is scored against a high of 10.
Engagement: Total number of occurences of a url
Stage: Refers to a stage of the process a url is in,

```

### Contents Trending

> `App`:
![image](/img/Docs/)


> `Db`: Contents

```js
Fetches the latest contents from the Contents table. (interms of date)
Score: Score is calculated by comparing the conversions of each url against all the conversions and then each url is scored against a high of 10.
Engagement: Total number of occurences of a url
Stage: Refers to a stage of the process a url is in
Last Seen: Last seen refers to the date the url was last seen in the entries.

```

### Top Performing Content

> `App`:
![image](/img/Docs/)


> `Db`: Contents

```js
Fetches entries from contents table sorted in decresing order of engagements 
Engagements Generated: Total number of occurences of a url
Last Seen: Last seen refers to the date the url was last seen in the entries.
```

### Top Keywords Used

> `App`:
![image](/img/Docs/)


> `Db`: Contents

```js
Fetches entries from contents table sorted in decresing order of engagements and genereates a word cloud .
```

<a name="section-3.1"></a>
## Contents Engagement 

### Performance

> `App`:
![image](/img/Docs/)


> `Db`: topentry_u

```js
Fetches entries from the db table using params: PageName, PageURL and Date.
```

### Engagement

> `App`:
![image](/img/Docs/)


> `Db`: Contents

```js
Fetches entries from the db table using params: PageName and PageURL.
The days functionality doesn't work since the db has an aggregate data available.
```

### Top Entry Points

> `App`:
![image](/img/Docs/)


> `Db`: topentry_u

```js
Fetches entries from the db table using params: PageName, PageURL and Date.
```


### Visitor Engagement
> `App`:
![image](/img/Docs/)


> `Db`: clickmap

```js
Fetches 500 entries from the db table using params: PageName and PageURL.

click map currently has static values and needed 3 test cases to figure out the right scenario.


var point = {
x = 'x coordinate', ( replace with x value)
y='y coordinate', ( replace with y value)
value = 'val'   }
a) First test case is to map 5 static data points without value field 
ex coordinates: { (1,2), (3,4),(7,2),(8,1),(6,2)}
b) Second test case is to map 10 static data points with recurring data points and no value field
ex coordinates: {(1,2), (3,4),(7,2),(8,1),(6,2),(1,4),(4,8),(3,6),(3,6),(3,6)}
c) Third case is to map the same points as b and have an incrementing value for every recurring value for value field
ex coordinates: {(1,2), (3,4),(7,2),(8,1),(6,2),(1,4),(4,8),(3,6),(3,6),(3,6)}
value field for (3,6) would be 3
"Please use random data points for mapping instead of the example coordinates"

```

<a name="section-4"></a>

## Leads Page

Database Tables : --,--,--

### Lead Funnel

> `App`:
![image](/img/Docs/)


> `Db`: VisitorBreakdown_u

```js
Total Users: The number of unique e_ids. 
Engaged Users: The number of unique e_ids returning after 60 mins. 
Converted Users: The number of unique emails.
```

### Lead Progression

> `App`:
![image](/img/Docs/)


> `Db`: lead_progression

```js
Fetches the entries from db using client_id. 
```
### Recent Leads

> `App`:
![image](/img/Docs/)


> `Db`: Lead_ids

```js
Fetches the entries from db using client_id. 

User ID: e_id from the table. 
Channel: Each e_ids first visit i.e.., PageURL is exposed through sorting algo. and lands in one of the channels. 
Latest Content: Latest Page Name of the e_id. 
Stage: Currently the table only consists of converted e_ids. 
Last Seen: Last visit date of e_ids. 

For catapult systems only we show a column called email.
Email: Email id of the converted e_id. 

```

<a name="section-4.1"></a>

## Leads ID Page

### User ID

> `App`:
![image](/img/Docs/)


> `Db`: Lead_ids

```js
Fetches the entries from db using client_id. 

User ID: e_id from the table. 
Channel: Each e_ids first visit i.e.., PageURL is exposed through sorting algo. and lands in one of the channels. 
Last Seen: Last visit date of e_ids. 
Device: The device the e_id uses (ex. laptop or desktop)
Browser: The browser the e_id uses (ex. Firefox)
Blocks Cookie: Whether the e_id blocks cookies
Delete Cookie: Whether the e_id deletes cookies

For catapult systems `only` we show a column called email.
Email: Email id of the converted e_id. 

```

### Lead Progression

> `App`:
![image](/img/Docs/)


> `Db`: lead_progression

```js
Fetches the entries from db using client_id. 
```

### Path to Conversion
> `App`:
![image](/img/Docs/)


> `Db`: up

```js
Fetches the entries from db using client_id and e_id. 
Displays a tree which starts with channel, shows the conversion path and ends with converted path url.
```
