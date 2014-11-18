#!/bin/bash

DIFF_ID=$1
PH_ID=$2

# Suppress output
set +x

# Update iatoki-commons
cd ../iatoki-commons
git pull
cd ../workspace

if [ "${PH_ID}" == "0" ] ; then
        ../iatoki-commons/jenkins-scripts/prepare-build-diff.sh ${DIFF_ID} ${PH_ID}
else
        ../iatoki-commons/jenkins-scripts/prepare-build-commit.sh ${DIFF_ID} ${PH_ID}
fi

# Bring back output
set -x
