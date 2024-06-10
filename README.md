![Example of landing page](https://i.imgur.com/cBoKYQy.jpeg)

# Usage
This is a landing page that will deliver followers to a username for either TikTok or Instagram upon completion of the content locker. This is done by using a service called SMMGlobe.

# Uploading
You'll want to upload all the files to your host. Personally we recommend using shared hosting to start. We have a video covering this in detail [here](https://learn.ogads.com/course/2-how-to-make-a-website/watch?video=16).

# How It Works
The website holds all it's settings in the `.env` file. You'll need to create it and copy the settings from `.env.example`.

You'll need an API key for OGAds which can be generated [here](https://members.ogads.com/tools/offer-api).

You'll need an API key for SMMGlobe which can be generated [here](https://smmglobe.com/account).

You'll need to specify the service ID for followers if they're no longer valid. This is based on the service ID from SMMGlobe. You can find the list on their dashboard [here](https://smmglobe.com/services).

Finally, you'll need a database and to provide the connection details in the `.env` file.

We recommend you leave `OFFERS_LIMIT` and `OFFERS_CTYPE` to the default settings in the `.env` file.

Finally, you'll need to make sure you update the postback URL on OGAds. This needs to be your URL that looks like the following `https://example.com/api/postback.php?offer_id={offer_id}&aff_sub4={aff_sub4}&ip={session_ip}`

# Follower Amount
Followers are delivered to a user after they complete X amount of offers. The offers required to complete are dependent on the follower amount selected.

A user can change the follower amount selected at any moment by going back and completing the form again.

### Conversions and Followers
- 250 followers = 1 offer
- 500 followers = 2 offers
- 1000 followers = 3 offers

# License
MIT License

Copyright (c) 2024 OGAds

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.