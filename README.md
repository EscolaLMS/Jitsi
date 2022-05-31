# Jitsi

Jitsi integration

[![codecov](https://codecov.io/gh/EscolaLMS/Jitsi/branch/main/graph/badge.svg?token=NRAN4R8AGZ)](https://codecov.io/gh/EscolaLMS/Jitsi)
[![phpunit](https://github.com/EscolaLMS/Jitsi/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Jitsi/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/jitsi)](https://packagist.org/packages/escolalms/jitsi)
[![downloads](https://img.shields.io/packagist/v/escolalms/jitsi)](https://packagist.org/packages/escolalms/jitsi)
[![downloads](https://img.shields.io/packagist/l/escolalms/jitsi)](https://packagist.org/packages/escolalms/jitsi)
[![Maintainability](https://api.codeclimate.com/v1/badges/0fe584397e06ef32618f/maintainability)](https://codeclimate.com/github/EscolaLMS/Jitsi/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/0fe584397e06ef32618f/test_coverage)](https://codeclimate.com/github/EscolaLMS/Jitsi/test_coverage)


## What does it do
This package introduce just a facade that you can use to generate parameters for jitsi player

## Installing
- `composer require escolalms/jitsi`
- Setup environmental config to point to Jitsi service - use either `env` file or [Settings package](https://github.com/EscolaLMS/Settings) (settings should be visible in the settings endpoint)

```php
return [
    'host' => env('JITSI_HOST', 'meet-stage.escolalms.com'),
    'app_id' => env('JITSI_APP_ID', 'meet-id'),
    'secret' => env('JITSI_APP_SECRET', 'secret'),
    'package_status' => 'enabled',
];
```

If `app_id` or `secret` service will skip `JWT` token generation.

Once you provide the above you can generate parameters, example from tinker

```php
\EscolaLms\Jitsi\Facades\Jitsi::getChannelData(App\Models\User::find(1), "czesc ziomku", true, ['logoImageUrl'=>'https://escola.pl/_next/image?url=%2Fimages%2Flogo-escola.svg&w=3840&q=75'])
```

would generate some thing like

```php
[
     "data" => [
       "domain" => "meet-stage.escolalms.com",
       "roomName" => "czescZiomku",
       "configOverwrite" => [
         "logoImageUrl" => "https://escola.pl/_next/image?url=%2Fimages%2Flogo-escola.svg&w=3840&q=75",
       ],
       "interfaceConfigOverwrite" => [
       ],
       "userInfo" => [
         "id" => 1,
         "name" => "Osman Kanu",
         "displayName" => "Osman Kanu",
         "email" => "student@escola-lms.com",
         "moderator" => true,
       ],
       "jwt" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJtZWV0LWlkIiwiYXVkIjoibWVldC1pZCIsInN1YiI6Im1lZXQtc3RhZ2UuZXNjb2xhbG1zLmNvbSIsImV4cCI6MTY0MzY1OTM1NCwicm9vbSI6ImN6ZXNjWmlvbWt1IiwidXNlciI6eyJpZCI6MSwibmFtZSI6Ik9zbWFuIEthbnUiLCJkaXNwbGF5TmFtZSI6Ik9zbWFuIEthbnUiLCJlbWFpbCI6InN0dWRlbnRAZXNjb2xhLWxtcy5jb20iLCJtb2RlcmF0b3IiOmZhbHNlfX0.xnFV-Kk63c3YRADzkSQLz6FP71yfEUO7Q53isFGkv_U",
     ],
     "host" => "meet-stage.escolalms.com",
     "url" => "https://meet-stage.escolalms.com/czescZiomku?jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJtZWV0LWlkIiwiYXVkIjoibWVldC1pZCIsInN1YiI6Im1lZXQtc3RhZ2UuZXNjb2xhbG1zLmNvbSIsImV4cCI6MTY0MzY1OTM1NCwicm9vbSI6ImN6ZXNjWmlvbWt1IiwidXNlciI6eyJpZCI6MSwibmFtZSI6Ik9zbWFuIEthbnUiLCJkaXNwbGF5TmFtZSI6Ik9zbWFuIEthbnUiLCJlbWFpbCI6InN0dWRlbnRAZXNjb2xhLWxtcy5jb20iLCJtb2RlcmF0b3IiOmZhbHNlfX0.xnFV-Kk63c3YRADzkSQLz6FP71yfEUO7Q53isFGkv_U",
   ]
```

pass this object into endpoint that generates jitsi call. You should definitely [read the manual before](https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-web-sdk).

Example

```tsx
import React from "react";
import JitsiMeeting from "@jitsi/web-sdk/lib/components/JitsiMeeting";
import type {
  IJitsiMeetExternalApi,
  IJitsiMeetingProps,
} from "@jitsi/web-sdk/lib/types";

const dataFromEndpoint = {
  domain: "meet-stage.escolalms.com",
  roomName: "czescZiomku",
  configOverwrite: {},
  interfaceConfigOverwrite: {},
  userInfo: {
    displayName: "Osman Kanu",
    email: "student@escola-lms.com",
  },
  jwt: "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJtZWV0LWlkIiwiYXVkIjoibWVldC1pZCIsInN1YiI6Im1lZXQtc3RhZ2UuZXNjb2xhbG1zLmNvbSIsImV4cCI6MTY0MzY1OTM1NCwicm9vbSI6ImN6ZXNjWmlvbWt1IiwidXNlciI6eyJpZCI6MSwibmFtZSI6Ik9zbWFuIEthbnUiLCJkaXNwbGF5TmFtZSI6Ik9zbWFuIEthbnUiLCJlbWFpbCI6InN0dWRlbnRAZXNjb2xhLWxtcy5jb20iLCJtb2RlcmF0b3IiOmZhbHNlfX0.xnFV-Kk63c3YRADzkSQLz6FP71yfEUO7Q53isFGkv_U",
};

const data: IJitsiMeetingProps = {
  ...dataFromEndpoint,
  onApiReady: (api) => console.log("api ready", api),
};

function App() {
  return (
    <div className="App">
      <JitsiMeeting {...data} />
    </div>
  );
}

export default App;
```

## Tests

Run `./vendor/bin/phpunit --filter 'EscolaLms\\Jitsi\\Tests'` to run tests. See [tests](tests) folder as it's quite good staring point as documentation appendix.

