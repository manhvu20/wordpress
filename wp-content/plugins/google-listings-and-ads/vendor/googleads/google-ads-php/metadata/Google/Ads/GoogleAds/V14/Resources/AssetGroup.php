<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v14/resources/asset_group.proto

namespace GPBMetadata\Google\Ads\GoogleAds\V14\Resources;

class AssetGroup
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();
        if (static::$is_initialized == true) {
          return;
        }
        \GPBMetadata\Google\Api\Resource::initOnce();
        \GPBMetadata\Google\Api\FieldBehavior::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
0google/ads/googleads/v14/enums/ad_strength.protogoogle.ads.googleads.v14.enums"�
AdStrengthEnum"s

AdStrength
UNSPECIFIED 
UNKNOWN
PENDING

NO_ADS
POOR
AVERAGE
GOOD
	EXCELLENTB�
"com.google.ads.googleads.v14.enumsBAdStrengthProtoPZCgoogle.golang.org/genproto/googleapis/ads/googleads/v14/enums;enums�GAA�Google.Ads.GoogleAds.V14.Enums�Google\\Ads\\GoogleAds\\V14\\Enums�"Google::Ads::GoogleAds::V14::Enumsbproto3
�
7google/ads/googleads/v14/enums/asset_group_status.protogoogle.ads.googleads.v14.enums"n
AssetGroupStatusEnum"V
AssetGroupStatus
UNSPECIFIED 
UNKNOWN
ENABLED

PAUSED
REMOVEDB�
"com.google.ads.googleads.v14.enumsBAssetGroupStatusProtoPZCgoogle.golang.org/genproto/googleapis/ads/googleads/v14/enums;enums�GAA�Google.Ads.GoogleAds.V14.Enums�Google\\Ads\\GoogleAds\\V14\\Enums�"Google::Ads::GoogleAds::V14::Enumsbproto3
�
4google/ads/googleads/v14/resources/asset_group.proto"google.ads.googleads.v14.resources7google/ads/googleads/v14/enums/asset_group_status.protogoogle/api/field_behavior.protogoogle/api/resource.proto"�

AssetGroupB
resource_name (	B+�A�A%
#googleads.googleapis.com/AssetGroup
id	 (B�A;
campaign (	B)�A�A#
!googleads.googleapis.com/Campaign
name (	B�A

final_urls (	
final_mobile_urls (	U
status (2E.google.ads.googleads.v14.enums.AssetGroupStatusEnum.AssetGroupStatus
path1 (	
path2 (	S
ad_strength
 (29.google.ads.googleads.v14.enums.AdStrengthEnum.AdStrengthB�A:^�A[
#googleads.googleapis.com/AssetGroup4customers/{customer_id}/assetGroups/{asset_group_id}B�
&com.google.ads.googleads.v14.resourcesBAssetGroupProtoPZKgoogle.golang.org/genproto/googleapis/ads/googleads/v14/resources;resources�GAA�"Google.Ads.GoogleAds.V14.Resources�"Google\\Ads\\GoogleAds\\V14\\Resources�&Google::Ads::GoogleAds::V14::Resourcesbproto3'
        , true);
        static::$is_initialized = true;
    }
}

