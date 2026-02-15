# API Map

This API map is derived from the official Postman collection and cross-checked with Meta documentation.

## Supported Endpoints

| Request (Postman) | Method | Path | Auth | Body | Notes |
| --- | --- | --- | --- | --- | --- |
| Send Text Message | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | messaging_product=whatsapp, type=text |
| Send Message Template Text | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | type=template |
| Send Image Message by ID | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | type=image, image.id |
| Send Image Message by URL | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | type=image, image.link |
| Send Document Message by ID | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | type=document, document.id |
| Send Document Message by URL | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | type=document, document.link |
| Send Video Message by ID | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | type=video, video.id |
| Send Video Message by URL | POST | /{version}/{phone-number-id}/messages | Bearer | JSON | type=video, video.link |
| Mark Message As Read | PUT | /{version}/{phone-number-id}/messages | Bearer | JSON | status=read, message_id |
| Upload Media | POST | /{version}/{phone-number-id}/media | Bearer | multipart/form-data | media upload by file/stream |
| Retrieve Media URL | GET | /{version}/{media-id} | Bearer | none | phone_number_id query optional (validation) |
| Download Media | GET | {media-url} | Bearer | none | media-url is full URL |
| Webhook Payload Reference | N/A | N/A | N/A | JSON | parse webhook notifications (messages/statuses) |
| Subscribe to a WABA | POST | /{version}/{waba-id}/subscribed_apps | Bearer | none | webhook subscription |
| List WABA subscriptions | GET | /{version}/{waba-id}/subscribed_apps | Bearer | none | webhook subscription |
| Unsubscribe from a WABA | DELETE | /{version}/{waba-id}/subscribed_apps | Bearer | none | webhook subscription |
| Override webhook callback | POST | /{version}/{waba-id}/subscribed_apps | Bearer | JSON | override_callback_uri + verify_token |
| Get WABA | GET | /{version}/{waba-id} | Bearer | none | business account details |
| Get owned WABAs | GET | /{version}/{business-id}/owned_whatsapp_business_accounts | Bearer | none | business portfolio |
| Get shared WABAs | GET | /{version}/{business-id}/client_whatsapp_business_accounts | Bearer | none | business portfolio |
| Get phone numbers | GET | /{version}/{waba-id}/phone_numbers | Bearer | none | list phone numbers |
| Get phone number by ID | GET | /{version}/{phone-number-id} | Bearer | none | phone number details |
| Get display name status | GET | /{version}/{phone-number-id}?fields=name_status | Bearer | none | beta field |
| Request verification code | POST | /{version}/{phone-number-id}/request_code | Bearer | JSON | code_method + locale |
| Verify code | POST | /{version}/{phone-number-id}/verify_code | Bearer | JSON | code |
| Set two-step verification | POST | /{version}/{phone-number-id} | Bearer | JSON | pin |
| Register phone number | POST | /{version}/{phone-number-id}/register | Bearer | JSON | messaging_product + pin |
| Deregister phone number | POST | /{version}/{phone-number-id}/deregister | Bearer | none | success response |
| Get business profile | GET | /{version}/{phone-number-id}/whatsapp_business_profile | Bearer | none | fields query optional |
| Update business profile | POST | /{version}/{phone-number-id}/whatsapp_business_profile | Bearer | JSON | messaging_product + profile fields |
| Create upload session | POST | /{version}/app/uploads | Bearer | none | query: file_length, file_type, file_name |
| Upload file data | POST | /{version}/{upload-id} | Bearer | binary | file_offset header + Content-Type |
| Query upload status | GET | /{version}/{upload-id} | Bearer | none | resumable upload status |
| Get commerce settings | GET | /{version}/{phone-number-id}/whatsapp_commerce_settings | Bearer | none | WhatsApp commerce flags |
| Update commerce settings | POST | /{version}/{phone-number-id}/whatsapp_commerce_settings | Bearer | none | query: is_cart_enabled, is_catalog_visible |
| Get QR code | GET | /{version}/{phone-number-id}/message_qrdls/{qr-code-id} | Bearer | none | fetch QR by code |
| List QR codes | GET | /{version}/{phone-number-id}/message_qrdls | Bearer | none | fields and code query optional |
| Create QR code | POST | /{version}/{phone-number-id}/message_qrdls | Bearer | JSON | prefilled_message, generate_qr_image |
| Update QR code | POST | /{version}/{phone-number-id}/message_qrdls | Bearer | JSON | prefilled_message, code |
| Delete QR code | DELETE | /{version}/{phone-number-id}/message_qrdls/{qr-code-id} | Bearer | none | success response |
| Get analytics | GET | /{version}/{waba-id}?fields=analytics... | Bearer | none | analytics fields expression |
| Get conversation analytics | GET | /{version}/{waba-id}?fields=conversation_analytics... | Bearer | none | analytics fields expression |
| Create flow | POST | /{version}/{waba-id}/flows | Bearer | multipart/form-data | name, categories, optional clone_flow_id and endpoint_uri |
| Migrate flows | POST | /{version}/{waba-id}/migrate_flows | Bearer | multipart/form-data | source_waba_id, optional source_flow_names |
| Get flow | GET | /{version}/{flow-id} | Bearer | none | fields query optional |
| Get flow preview | GET | /{version}/{flow-id}?fields=preview.invalidate(...) | Bearer | none | optional date_format |
| List flows | GET | /{version}/{waba-id}/flows | Bearer | none | list flows |
| Update flow metadata | POST | /{version}/{flow-id} | Bearer | multipart/form-data | name, categories, optional endpoint_uri |
| Upload flow JSON | POST | /{version}/{flow-id}/assets | Bearer | multipart/form-data | asset_type=FLOW_JSON + file |
| List flow assets | GET | /{version}/{flow-id}/assets | Bearer | none | list assets |
| Publish flow | POST | /{version}/{flow-id}/publish | Bearer | none | success response |
| Deprecate flow | POST | /{version}/{flow-id}/deprecate | Bearer | none | success response |
| Delete flow | DELETE | /{version}/{flow-id} | Bearer | none | success response |
| Set flow encryption key | POST | /{version}/{phone-number-id}/whatsapp_business_encryption | Bearer | multipart/form-data | business_public_key |
| Get flow encryption key | GET | /{version}/{phone-number-id}/whatsapp_business_encryption | Bearer | none | encryption public key |
| Get flow endpoint metrics | GET | /{version}/{flow-id}?fields=... | Bearer | none | metrics fields expression |
| Get credit lines | GET | /{version}/{business-id}/extendedcredits | Bearer | none | fields query optional |
| Get blocked users | GET | /{version}/{phone-number-id}/block_users | Bearer | none | list blocked users |
| Block users | POST | /{version}/{phone-number-id}/block_users | Bearer | JSON | messaging_product + block_users |
| Unblock users | DELETE | /{version}/{phone-number-id}/block_users | Bearer | JSON | messaging_product + block_users |
| Get business portfolio | GET | /{version}/{business-id}?fields=... | Bearer | none | business fields expression |

## Notes

- Base URL: https://graph.facebook.com (default version v24.0)
- Path variables are derived from the Postman collection and confirmed with Meta docs.
- Message endpoint is POST /{Version}/{Phone-Number-ID}/messages for all send message types.
- Mark as read uses PUT /{Version}/{Phone-Number-ID}/messages with status=read and message_id.
- Media URL retrieval supports optional phone_number_id query parameter for validation.
- Media download requires Authorization header and the URL expires in ~5 minutes.
