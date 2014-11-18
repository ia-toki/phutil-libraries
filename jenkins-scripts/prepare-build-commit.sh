#!/bin/bash

DIFF_ID=$1
PH_ID=$2

# Get diff object
DIFF=$(echo "{\"ids\" : [${DIFF_ID}]}" | arc call-conduit differential.querydiffs)

# Parse revision ID
REV_ID=$(echo $DIFF | jq .response[].revisionID | sed "s/\"//g")

# Add build status as comment on revision page
echo "{\"revision_id\" : ${REV_ID}, \"message\" : \"Build for **Revision ${REV_ID}** started: ${BUILD_URL}\"}" |  arc call-conduit differential.createcomment
