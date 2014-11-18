#!/bin/bash

DIFF_ID=$1
PH_ID=$2

# Postpone unit test result
echo "{\"diff_id\" : ${DIFF_ID}, \"name\" : \"Jenkins\", \"result\" : \"postponed\"}" | arc call-conduit differential.updateunitresults

# Get diff object
DIFF=$(echo "{\"ids\" : [${DIFF_ID}]}" | arc call-conduit differential.querydiffs)

# Parse base commit and revision ID
BASE_COMMIT=$(echo $DIFF | jq .response[].sourceControlBaseRevision | sed "s/\"//g")
REV_ID=$(echo $DIFF | jq .response[].revisionID | sed "s/\"//g")

# Add build status as comment on revision page
echo "{\"revision_id\" : ${REV_ID}, \"message\" : \"Build for **Diff ${DIFF_ID}** started: ${BUILD_URL}\"}" |  arc call-conduit differential.createcomment

# Check out revision
git checkout ${BASE_COMMIT}
arc patch ${REV_ID}
